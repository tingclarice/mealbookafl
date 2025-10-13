<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Review;
use Illuminate\Http\Request;

class MealController extends Controller
{
    // Untuk halaman /menu (list + filter)
    public function index(Request $request)
    {
        $query = Meal::where('isAvailable', true);
        
        $currentCategory = $request->get('category');
        
        if ($currentCategory) {
            $query->where('category', $currentCategory);
        }
        
        $meals = $query->paginate(12);
        
        $categories = [
            'MEAL' => 'Makanan',
            'SNACK' => 'Snack',
            'DRINK' => 'Minuman'
        ];
        
        return view('menu.index', compact('meals', 'categories', 'currentCategory'));
    }

    // Untuk halaman /menu/{id} (detail)
    public function show($id){
        $meal = Meal::with(['reviews.user'])->findOrFail($id);
        $reviews = $meal->reviews;
        $averageRating = round($reviews->avg('rate'), 1);
        $reviewCount = $reviews->count();
        $latestReviews = $reviews->sortByDesc('created_at')->take(2);
        $suggestedMeals = Meal::where('id', '!=', $meal->id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('menu.show', compact('meal', 'averageRating', 'reviewCount', 'latestReviews', 'suggestedMeals'));
    }

    public function reviews($id){
        $meal = Meal::with(['reviews.user'])->findOrFail($id);
        $reviews = $meal->reviews->sortByDesc('created_at');
        $averageRating = round($reviews->avg('rate'), 1);
        $reviewCount = $reviews->count();
        return view('menu.reviews', compact('meal', 'reviews', 'averageRating', 'reviewCount'));
    }
}
