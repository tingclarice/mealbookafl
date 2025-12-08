<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shopApprovals(){
        $pendingShops = Shop::where('status', 'PENDING')->get();
        return view('dashboard.shopApprovals', compact('pendingShops'));
    }

    public function accept(Shop $shop){
        $shop->update(['status' => 'OPEN']);
        return back()->with('success', 'Shop accepted successfully');
    }

    public function decline(Shop $shop){
        $shop->update(['status' => 'REJECTED']);
        return back()->with('success', 'Shop declined successfully');
    }

    public function suspend(Shop $shop){
        $shop->update(['status' => 'SUSPENDED']);
        return back()->with('success', 'Shop suspended successfully');
    }
}
