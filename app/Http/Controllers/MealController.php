<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

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
        
        $suggestedMeals = Meal::available()
                                ->where('id', '!=', $id)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
        
        return view('menu.show', compact('meal', 'suggestedMeals'));
    }
}
