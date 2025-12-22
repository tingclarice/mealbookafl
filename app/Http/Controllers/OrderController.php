<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use DB;
use Illuminate\Http\Request;
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

    public function createOrder(Request $request)
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
        $fee = 1000;
        $totalAmount = $subtotal + $fee;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
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
}
