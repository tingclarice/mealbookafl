<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function cart(){
        $cartItems = CartItem::all();
        return view('cart', ["cartItems" => $cartItems]);
    }
}
