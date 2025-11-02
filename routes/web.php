<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Login Google
Route::get('/auth/google', [AuthController::class, 'loginGoogle'])->name("login");
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleResponse']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home & About
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Menu / Meal
Route::get('/menu', [MealController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MealController::class, 'show'])->name('menu.show');
Route::get('/menu/{id}/reviews', [MealController::class, 'reviews'])->name('menu.reviews');

// Cart
Route::get('/cart', [CartController::class, 'cart'])->name('cart');

// Dashboard
Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware(['auth', AdminMiddleware::class])->name('dashboard');


// Test Page can be deleted later
Route::get('test', function(){
    $user = Auth::user();
    return view('tes', ["user"=>$user]);
});