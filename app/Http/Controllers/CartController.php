<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function cart(){
        $cartItems = CartItem::where('user_id', 1)->with('meal')->get();

    // 2. Calculate totals using the collection
    $subtotal = $cartItems->sum(function ($item) {
        return $item->quantity * $item->meal->price;
    });

    $fee = 1000; // Example fee
    $totalPrice = $subtotal + $fee;

    // 3. Pass the original collection and totals to the view
    return view('cart', [
        'cartItems'  => $cartItems,
        'subtotal'   => $subtotal,
        'fee'        => $fee,
        'totalPrice' => $totalPrice,
    ]);

        // $subtotal = 
        return view('cart', ["cartItems" => $formattedCart]);
        // return view('testpage', ["data"=> $formattedCart]);
    }
}
