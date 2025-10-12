<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Models\Meal;

Route::get('/', function () {
    $meals = Meal::where('isAvailable', true)->take(6)->get();
    return view('home', compact('meals'));
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/menu', function () {
    $query = Meal::where('isAvailable', true);
    
    // Filter by category if provided
    if (request('category')) {
        $query->where('category', request('category'));
    }
    
    $meals = $query->paginate(12); // 12 items per page
    return view('menu.index', compact('meals'));
})->name('menu');

// Route untuk detail meal - pakai menu.show
Route::get('/menu/{id}', function ($id) {
    $meal = Meal::with('reviews.user')->findOrFail($id);
    $suggestedMeals = Meal::where('isAvailable', true)
                            ->where('id', '!=', $id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();
    return view('menu.show', compact('meal', 'suggestedMeals'));
})->name('menu.show');


Route::get('/cart', [CartController::class, 'cart']);

