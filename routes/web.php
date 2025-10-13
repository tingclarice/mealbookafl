<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use Illuminate\Support\Facades\Route;
use App\Models\Meal;

Route::get('/', function () {
    $meals = Meal::where('isAvailable', true)->take(6)->get();
    return view('home', compact('meals'));
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/menu', [MealController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MealController::class, 'show'])->name('menu.show');
Route::get('/menu/{id}/reviews', [MealController::class, 'reviews'])->name('menu.reviews');


Route::get('/cart', [CartController::class, 'cart'])->name('cart');

