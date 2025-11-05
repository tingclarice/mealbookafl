<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Login Google
Route::get('/auth/google', [AuthController::class, 'loginGoogle'])->name("login");
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleResponse']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Static Pages (Home, About)
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Menu / Meal
Route::get('/menu', [MealController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MealController::class, 'show'])->name('menu.show');
Route::get('/menu/{id}/reviews', [MealController::class, 'reviews'])->name('menu.reviews');
Route::post('/meals', [MealController::class, 'store'])->name('meals.store');
Route::put('/meals/{id}', [MealController::class, 'update'])->name('meals.update');
Route::delete('/meals/{id}', [MealController::class, 'destroy'])->name('meals.destroy');

// Cart
Route::get('/cart', [CartController::class, 'cart'])->name('cart');

// User
Route::patch('admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboardMeal'])->middleware(['auth', AdminMiddleware::class])->name('dashboard');
Route::get('/dashboard/users', [DashboardController::class, 'dashboardUsers'])->middleware(['auth', AdminMiddleware::class])->name('dashboard.users');


// Test Page can be deleted later
Route::get('test', function(){
    $data = Meal::all();
    return view('tes', compact('data'));
});