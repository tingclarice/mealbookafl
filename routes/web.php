<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/menu', [MealController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MealController::class, 'show'])->name('menu.show');
Route::get('/menu/{id}/reviews', [MealController::class, 'reviews'])->name('menu.reviews');

Route::get('/cart', [CartController::class, 'cart'])->name('cart');

