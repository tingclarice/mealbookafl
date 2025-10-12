<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart(){
        $cartItems = CartItem::where('user_id', 1)->with('meal')->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->meal->price;
        });

        $fee = 1000; 
        $totalPrice = $subtotal + $fee;

        return view('cart', [
            'cartItems'  => $cartItems,
            'subtotal'   => $subtotal,
            'fee'        => $fee,
            'totalPrice' => $totalPrice,
        ]);
            return view('cart', ["cartItems" => $cartItems]);
    }
}
