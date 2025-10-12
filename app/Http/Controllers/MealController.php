<?php

namespace App\Http\Controllers;

use App\Models\Meal;
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
            'DRINKS' => 'Minuman'
        ];
        
        return view('menu.index', compact('meals', 'categories', 'currentCategory'));
    }

    // Untuk halaman /menu/{id} (detail)
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
}