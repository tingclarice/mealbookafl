<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\Review;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $query = Meal::where('isAvailable', true);
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $meals = $query->paginate(12);
        return view('menu.index', compact('meals'));
    }

    public function show($id)
    {
        $meal = Meal::with('reviews.user')->findOrFail($id);
        
        $suggestedMeals = Meal::where('isAvailable', true)
                                ->where('id', '!=', $id)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
        
        return view('menu.show', compact('meal', 'suggestedMeals'));
    }

    public function reviews($id){
        $meal = Meal::with(['reviews.user'])->findOrFail($id);
        $reviews = $meal->reviews->sortByDesc('created_at');
        $averageRating = round($reviews->avg('rate'), 1);
        $reviewCount = $reviews->count();
        return view('menu.reviews', compact('meal', 'reviews', 'averageRating', 'reviewCount'));
    }
}
