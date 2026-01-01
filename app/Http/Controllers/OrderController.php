<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Shop;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Str;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createOrder(Shop $shop)
    {
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['meal', 'selectedOptions.optionValue'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        // Calculate total
        $subtotal = $cartItems->sum->total_price;
        $fee = 0;
        $totalAmount = $subtotal + $fee;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'order_status' => 'PENDING',
                'payment_status' => 'PENDING',
                'total_amount' => $totalAmount,
                'midtrans_order_id' => 'ORDER-' . uniqid() . '-' . time(),
                'snap_token' => "",
            ]);

            foreach ($cartItems as $item) {
                // Create OrderItem
                $orderItem = $order->items()->create([
                    'meal_id' => $item->meal_id,
                    'meal_name' => $item->meal->name,
                    'quantity' => $item->quantity,
                    'shop_id' => $item->meal->shop_id,
                    'price' => $item->meal->price,
                ]);

                // Create OrderItemOptions
                foreach ($item->selectedOptions as $cartOption) {
                    if ($cartOption->optionValue) {
                        $orderItem->options()->create([
                            'option_name' => $cartOption->optionValue->name,
                            'price' => $cartOption->optionValue->price,
                        ]);
                    }
                }
            }

            // get snap_token
            $itemDetails = [];
            foreach ($order->items as $orderItem) {
                // Base meal
                $itemDetails[] = [
                    'id' => 'MEAL-' . $orderItem->meal_id,
                    'price' => (int) $orderItem->price,
                    'quantity' => $orderItem->quantity,
                    'name' => $orderItem->meal_name,
                ];

                // Options / addons
                foreach ($orderItem->options as $option) {
                    $itemDetails[] = [
                        'id' => 'OPT-' . Str::slug($option->option_name),
                        'price' => (int) $option->price,
                        'quantity' => 1,
                        'name' => $option->option_name,
                    ];
                }
            }
            $params = [
                'transaction_details' => [
                    'order_id' => $order->midtrans_order_id,
                    'gross_amount' => (int) $order->total_amount,
                ],

                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                ],

                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();

            // Clear User's Cart
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('order.checkout', $order);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log the error for debugging
            \Log::error('Order Creation Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function checkout(Order $order)
    {
        // if user_id in order is different with the logged-in user_id
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.options');

        return view('checkout', compact('order'));
    }

    // Manual Update
    public function updateStatus(Request $request, Order $order)
    {
        // 1. Authorization: check if the logged-in user owns this order OR is staff/owner
        if (!$order->isStaffOrOwner()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Determine next status based on current status
        $newStatus = null;
        if ($order->order_status === 'PENDING' && $order->payment_status === 'PAID') {
            $newStatus = 'CONFIRMED';
        } elseif ($order->order_status === 'CONFIRMED') {
            $newStatus = 'READY';

            $message = "Halo " . $order->user->name . ",\n\n";
            $message .= "Pesanan Anda #" . $order->id . " *SIAP DIAMBIL*.\n\n";
            $message .= "Rincian Pesanan:\n";

            foreach ($order->items as $item) {
                $message .= "- " . $item->quantity . "x " . $item->meal_name . "\n";
            }

            $message .= "\nTotal Biaya: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
            $message .= "Status pembayaran: LUNAS (" . $order->payment_method . ")\n";
            $message .= "Silakan datang ke kantin untuk mengambil pesanan Anda.\n";
            $message .= "Terima kasih telah berbelanja di " . $order->shop->name . "!";
            
            GowaController::sendMessage($message, $order->user->phone);
        } elseif ($order->order_status === 'READY') {
            $newStatus = 'COMPLETED';
        }

        if (!$newStatus) {
            return redirect()->back()->with('error', 'Cannot update status. Order might be unpaid or already completed.');
        }

        // 3. Update Status
        $order->order_status = $newStatus;
        $order->save();

        return redirect()->route('shopOrders')->with('success', "Order updated to $newStatus!");
    }

    // Cancel Order (Buyer)
    public function cancel(Order $order)
    {
        // 1. Authorization: check if the logged-in user owns this order
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Validate State
        if ($order->payment_status === 'PAID') {
            return redirect()->back()->with('error', 'Cannot cancel a paid order.');
        }

        if ($order->order_status === 'CANCELLED') {
            return redirect()->back()->with('error', 'Order is already cancelled.');
        }

        // 3. Update Status
        $order->order_status = 'CANCELLED';
        $order->save();

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Check order status via QR Scan (without updating)
     */
    public function checkOrderViaQr(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        // Find using midtrans_order_id
        $order = Order::where('midtrans_order_id', $request->order_id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 404);
        }

        // Authorization check
        $user = auth()->user();
        $isAuthorized = $user->shops()->where('shops.id', $order->shop_id)->exists();

        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This order belongs to another shop.'
            ], 403);
        }

        // Return order info
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id, // keep using midtrans id for consistency
                'customer_name' => $order->user->name ?? 'Guest',
                'total_price' => number_format($order->total_price, 0, ',', '.'),
                'status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'items_count' => $order->items->count()
            ]
        ]);
    }

    /**
     * Complete order using QR Scan
     */
    public function completeOrderViaQr(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        // Find using midtrans_order_id (which is what we'll put in the QR)
        $order = Order::where('midtrans_order_id', $request->order_id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 404);
        }

        // Check if user is authorized (must be shop owner/staff)
        // We use the helper method on User model or check manually
        $user = auth()->user();
        $isAuthorized = $user->shops()->where('shops.id', $order->shop_id)->exists();

        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This order belongs to another shop.'
            ], 403);
        }

        if ($order->payment_status !== 'PAID') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not paid yet.'
            ], 400);
        }

        // Check if already completed
        if ($order->order_status === 'COMPLETED') {
            return response()->json([
                'success' => false,
                'message' => 'Order is already completed.'
            ], 400);
        }

        // Update status to COMPLETED
        $order->order_status = 'COMPLETED';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Order #{$order->id} marked as COMPLETED!",
            'order' => $order
        ]);
    }

    public function orderDetails(Order $order)
    {
        // 1. Authorization: check if the logged-in user owns this order OR is staff/owner
        if ($order->user_id !== auth()->id() && !$order->isStaffOrOwner()) {
            abort(403);
        }

        // 2. use load for loading relationships
        $order->load(['items.options', 'shop']);

        return view('orders.details-order', compact('order'));
    }


    // My Order (User POV)
    public function myOrders()
    {
        // Set Midtrans Config (will be used on Snap::getSnapToken)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Get ALL orders
        // Using 'latest()' puts the newest orders at the top
        $allOrder = Order::where('user_id', Auth::id())->latest()->get();

        // // Loop through orders to check if any pending token is expired
        // foreach ($allOrder as $order) {
        //     if ($order->payment_status == 'EXPIRED' && $order->snap_token) {

        //         // Regenerate Token
        //         $newOrderId = $order->id . '-' . Str::random(5);

        //         $params = [
        //             'transaction_details' => [
        //                 'order_id' => $newOrderId,
        //                 'gross_amount' => (int) $order->total_amount,
        //             ],
        //             'customer_details' => [
        //                 'first_name' => Auth::user()->name,
        //                 'email' => Auth::user()->email,
        //             ],
        //         ];

        //         try {
        //             $snapToken = Snap::getSnapToken($params);

        //             // Update the order with the new token
        //             $order->snap_token = $snapToken;
        //             $order->save();
        //         } catch (\Exception $e) {
        //             // Handle error if midtrans fails
        //             \Log::info($e->getMessage());
        //         }

        //     }
        // }

        // 3. FILTERING 
        $pendingOrder = $allOrder->where('order_status', 'PENDING');
        $confirmedOrder = $allOrder->where('order_status', 'CONFIRMED');
        $readyOrder = $allOrder->where('order_status', 'READY');
        $completedOrder = $allOrder->where('order_status', 'COMPLETED');

        return view('myorders', compact('allOrder', 'pendingOrder', 'confirmedOrder', 'readyOrder', 'completedOrder'));
    }


    // Shop order POV
    public function shopOrders()
    {
        $shop = Auth::user()->shops()->first();

        $allOrder = Order::where('shop_id', $shop->id)->latest()->get();
        $pendingPayOrder = $allOrder->where('payment_status', 'PENDING');
        $paidOrder = $allOrder->where('payment_status', 'PAID');

        $pendingOrder = $paidOrder->where('order_status', 'PENDING');
        $confirmedOrder = $paidOrder->where('order_status', 'CONFIRMED');
        $readyOrder = $paidOrder->where('order_status', 'READY');
        $completedOrder = $paidOrder->where('order_status', 'COMPLETED');

        return view('shopOrders.shopOrder', compact('allOrder', 'shop', 'pendingPayOrder', 'pendingOrder', 'paidOrder', 'confirmedOrder', 'readyOrder', 'completedOrder'));
    }

}
