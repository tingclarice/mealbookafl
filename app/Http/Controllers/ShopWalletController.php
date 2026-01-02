<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopWalletController extends Controller
{
    /**
     * Display the shop wallet.
     */
    public function index()
    {
        $shop = Auth::user()->shops->first();

        // Check if shop exists and is approved, although middleware should handle this
        if (!$shop || !$shop->isApproved()) {
            return redirect()->route('home')->with('error', 'Shop not found or not approved.');
        }

        $wallet = $shop->wallet;
        $transactions = $wallet->transactions()->latest()->paginate(10);

        return view('shop.wallet', compact('shop', 'wallet', 'transactions'));
    }
}
