<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Order;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Midtrans\Snap;
use Str;

class PageController extends Controller
{
    // Landing page
    public function index()
    {
        $meals = Meal::where('isAvailable', true)->take(6)->get();
        return view('home', compact('meals'));
    }

    // About Page
    public function about()
    {
        return view('about');
    }


    // Profile Settings
    public function settings(Request $request)
    {
        $pendingOwnedShop = Auth::user()->shops()
            ->wherePivot('role', 'OWNER')
            ->whereIn('status', ['PENDING', 'REJECTED'])
            ->first();
        $activeOwnedShop = Auth::user()->shops()
            ->wherePivot('role', "OWNER")
            ->whereIn('status', ["OPEN", "CLOSED"])
            ->first();
        $user = $request->user();
        return view('settings', compact('user', 'pendingOwnedShop', 'activeOwnedShop'));
    }


    // Order Success
    public function orderSuccess(Order $order)
    {
        return view('orderStatus.success', compact('order'));
    }

    // Order Failed
    public function orderFailed(Order $order)
    {
        return view('orderStatus.failed', compact('order'));
    }

    // My Orders
    public function myOrders()
    {
        // Set Midtrans Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

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


}
