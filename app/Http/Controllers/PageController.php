<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $allOrder = Order::where('user_id', Auth::user()->id)->get();
        $pendingOrder = Order::where('user_id', Auth::user()->id)->where('order_status', 'PENDING')->get();
        $confirmedOrder = Order::where('user_id', Auth::user()->id)->where('order_status', 'CONFIRMED')->get();
        $readyOrder = Order::where('user_id', Auth::user()->id)->where('order_status', 'READY')->get();
        $completedOrder = Order::where('user_id', Auth::user()->id)->where('order_status', 'COMPLETED')->get();

        return view('myOrders', compact('allOrder', 'pendingOrder', 'confirmedOrder', 'readyOrder', 'completedOrder'));
    }


}
