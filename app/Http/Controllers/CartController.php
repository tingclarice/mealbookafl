<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart(){
        $cartItems = CartItem::where('user_id', Auth::user()->id)->with('meal')->get();

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
    }

    public function addToCart(Request $request, $id){
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to add to cart.');
        }

        $meal = Meal::findOrFail($id);

        // Check if this meal already exists in user's cart
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('meal_id', $meal->id)
            ->first();

        if ($cartItem) {
            // Increase quantity if already exists
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // Otherwise, create a new cart item
            CartItem::create([
                'user_id' => $user->id,
                'meal_id' => $meal->id,
                'quantity' => 1,
                'notes' => $request->input('notes', null), 
            ]);
        }

        return redirect()->back()->with('success', "{$meal->name} has been added to your cart!");
    }

    public function decrement($id){
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to modify your cart.');
        }

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Item not found in your cart.');
        }

        if ($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->save();
        } else {
            $cartItem->delete(); // remove item if quantity reaches 0
        }

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }


    public function removeFromCart($id){
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to modify your cart.');
        }

        // Find the cart item for this user
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $id) // ID here is the cart item ID, not meal ID
            ->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Item not found in your cart.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from your cart.');
    }
}
