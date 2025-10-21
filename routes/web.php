<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PageController;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Login Google
Route::get('/auth/google', [AuthController::class, 'loginGoogle'])->name("loginGoogle");
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleResponse']);

// Home & About
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Menu / Meal
Route::get('/menu', [MealController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MealController::class, 'show'])->name('menu.show');
Route::get('/menu/{id}/reviews', [MealController::class, 'reviews'])->name('menu.reviews');

// Cart
Route::get('/cart', [CartController::class, 'cart'])->name('cart');

