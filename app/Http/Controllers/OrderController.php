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

    public function orderDetails(Order $order)
    {
        // 1. Authorization: check if the logged-in user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // 2. use load for loading relationships
        $order->load('items.options');

        return view('orders.details-order', compact('order'));
    }


    // My Order (User POV)
    public function myOrders()
    {
        // Set Midtrans Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 1. Get ALL orders in ONE query
        // Using 'latest()' puts the newest orders at the top
        $allOrder = Order::where('user_id', Auth::id())->latest()->get();

        // 2. REGENERATION LOGIC
        // Loop through orders to check if any pending token is expired
        foreach ($allOrder as $order) {
            if ($order->order_status == 'PENDING' && $order->snap_token) {
                
                // Check if token was created more than 24 hours ago.
                if ($order->updated_at->diffInHours(now()) >= 24) {
                    
                    // Regenerate Token
                    $newOrderId = $order->id . '-' . Str::random(5);
                    
                    $params = [
                        'transaction_details' => [
                            'order_id' => $newOrderId,
                            'gross_amount' => (int) $order->total_price,
                        ],
                        'customer_details' => [
                            'first_name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                        ],
                    ];

                    try {
                        $snapToken = Snap::getSnapToken($params);
                        
                        // Update the order with the new token
                        $order->snap_token = $snapToken;
                        $order->save();
                    } catch (\Exception $e) {
                        // Handle error if midtrans fails
                        Log::info($e->getMessage());
                    }
                }
            }
        }

        // 3. FILTERING (Done in memory, no new DB queries)
        $pendingOrder   = $allOrder->where('order_status', 'PENDING');
        $confirmedOrder = $allOrder->where('order_status', 'CONFIRMED');
        $readyOrder     = $allOrder->where('order_status', 'READY');
        $completedOrder = $allOrder->where('order_status', 'COMPLETED');

        return view('myorders', compact('allOrder', 'pendingOrder', 'confirmedOrder', 'readyOrder', 'completedOrder'));
    }


    // Shop POV
    public function shopOrders(){
        $shop = Auth::user()->shops()->first();

        $allOrder = Order::where('shop_id', auth()->id())->get();
        $pendingOrder   = $allOrder->where('order_status', 'PENDING');
        $confirmedOrder = $allOrder->where('order_status', 'CONFIRMED');
        $readyOrder     = $allOrder->where('order_status', 'READY');
        $completedOrder = $allOrder->where('order_status', 'COMPLETED');

        return view('shopOrders.shopOrder', compact('allOrder', 'shop', 'pendingOrder', 'confirmedOrder', 'readyOrder', 'completedOrder'));
    }

}
