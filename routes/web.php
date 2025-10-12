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


Route::get('/cart', [CartController::class, 'cart']);

// Route:get('/r')