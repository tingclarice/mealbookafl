<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\CartItemOption;
use App\Models\Meal;
use App\Models\MealOptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart(){
        $cartItems = CartItem::where('user_id', Auth::user()->id)
            ->with(['meal', 'selectedOptions.optionValue.group'])
            ->get();

        // Calculate subtotal using the total_price attribute (includes options)
        $subtotal = $cartItems->sum(function ($item) {
            return $item->total_price;
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

        $meal = Meal::with('optionGroups.values')->findOrFail($id);

        // Validate that required option groups have selections
        $selectedOptions = $request->input('options', []); // Array of option value IDs

        // Check required options
        foreach ($meal->optionGroups as $group) {
            if ($group->is_required) {
                $hasSelection = false;
                foreach ($group->values as $value) {
                    if (in_array($value->id, $selectedOptions)) {
                        $hasSelection = true;
                        break;
                    }
                }
                if (!$hasSelection) {
                    return redirect()->back()->with('error', "Please select an option for '{$group->name}'.");
                }
            }
        }

        // Validate multiple selection rules
        foreach ($meal->optionGroups as $group) {
            if (!$group->is_multiple) {
                $selectedCount = 0;
                foreach ($group->values as $value) {
                    if (in_array($value->id, $selectedOptions)) {
                        $selectedCount++;
                    }
                }
                if ($selectedCount > 1) {
                    return redirect()->back()->with('error', "You can only select one option for '{$group->name}'.");
                }
            }
        }

         // Use transaction to ensure data consistency
        DB::beginTransaction();
        try {
            // Check if exact same item with same options exists
            $existingCartItem = $this->findExistingCartItem($user->id, $meal->id, $selectedOptions);

            if ($existingCartItem) {
                // Increase quantity if already exists
                $existingCartItem->quantity += 1;
                $existingCartItem->save();
                $cartItem = $existingCartItem;
            } else {
                // Create new cart item
                $cartItem = CartItem::create([
                    'user_id' => $user->id,
                    'meal_id' => $meal->id,
                    'quantity' => 1,
                    'notes' => $request->input('notes', null), 
                ]);

                // Save selected options
                foreach ($selectedOptions as $optionValueId) {
                    CartItemOption::create([
                        'cart_item_id' => $cartItem->id,
                        'meal_option_value_id' => $optionValueId,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "{$meal->name} has been added to your cart!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add item to cart. Please try again.');
        }
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

    // Helper method to find existing cart item with same options
    private function findExistingCartItem($userId, $mealId, $selectedOptions)
    {
        $cartItems = CartItem::where('user_id', $userId)
            ->where('meal_id', $mealId)
            ->with('selectedOptions')
            ->get();

        foreach ($cartItems as $item) {
            $existingOptions = $item->selectedOptions->pluck('meal_option_value_id')->sort()->values()->toArray();
            $newOptions = collect($selectedOptions)->sort()->values()->toArray();

            if ($existingOptions == $newOptions) {
                return $item;
            }
        }

        return null;
    }
}
