<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MealOptionController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Middleware\OwnerAndStaffForbiddenMiddleware;
use App\Http\Middleware\ShopApprovedMiddleware;

// ===== BREEZE AUTH ROUTES =====
// handles login, register, password reset, etc.
require __DIR__ . '/auth.php';


// PUBLIC ROUTES
// ===== GOOGLE AUTH =====
Route::get('/auth/google', [AuthController::class, 'loginGoogle'])->name("google.login");
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleResponse']);

// ===== LOGOUT (Works for regular auth & Google OAuth) =====
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== PUBLIC PAGES =====
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// ===== MENU (Public Routes) =====
Route::get('/menu', [MealController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MealController::class, 'show'])->name('menu.show');
// Route::get('/menu/{id}/reviews', [MealController::class, 'reviews'])->name('menu.reviews');

// ===== SHOP VIEW =====
Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shop.show');

// ===== Login Required =====
Route::middleware(['auth'])->group(function () {

    // Cart (logged in users only)
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/decrement/{id}', [CartController::class, 'decrement'])->name('cart.decrement');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // Order
    Route::post('/order/create/{shop}', [OrderController::class, 'createOrder'])->name('order.create');
    Route::get('/order/checkout/{order}', [OrderController::class, 'checkout'])->name('order.checkout');

    // Order Status
    Route::get('/order/success', [PageController::class, 'orderSuccess'])->name('order.success');
    Route::get('/order/failed', [PageController::class, 'orderFailed'])->name('order.failed');

    // My Orders
    Route::get('/myOrders', [OrderController::class, 'myOrders'])->name('myOrders');
    Route::get('/myOrders/{order}', [OrderController::class, 'orderDetails'])->name('order.details');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');



    // Settings
    Route::get('/settings', [PageController::class, 'settings'])->name('profile.edit');
    Route::patch('/settings', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/settings', [UserController::class, 'destroyProfile'])->name('profile.destroy');

    // Block Owner and Staff
    Route::middleware([OwnerAndStaffForbiddenMiddleware::class])->group(function () {
        // Register as Seller
        Route::post('/shops/request', [ShopController::class, 'request'])->name('shops.request');
    });
});


// ===== ADMIN ONLY ROUTES =====
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    // User Management
    Route::get('/dashboard/users', [DashboardController::class, 'dashboardUsers'])->name('dashboard.users');
    Route::patch('admin/users/{user}', [UserController::class, 'updateAdmin'])->name('admin.users.update');

    // Shop Approval 
    Route::get('/admin/shopApprovals', [ShopController::class, 'shopApprovals'])->name('admin.shopApprovals');
    Route::patch('/shops/{shop}/accept', [ShopController::class, 'accept'])->name('shops.accept');
    Route::patch('/shops/{shop}/decline/{message}', [ShopController::class, 'decline'])->name('shops.decline');
    Route::patch('/shops/{shop}/suspend/{message}', [ShopController::class, 'suspend'])->name('shops.suspend');

});


// ===== STAFF & OWNER ONLY ROUTES =====
Route::middleware(['auth', StaffMiddleware::class])->group(function () {
    // === Owned Shop Must Active (PENDING, SUSPENDED, REJECTED shop are not allowed) ===
    Route::middleware([ShopApprovedMiddleware::class])->group(function () {
        // Update Shop Information
        Route::patch('/shops/update', [ShopController::class, 'update'])->name('shop.update');

        // Meal Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboardMeal'])->name('dashboard');

        // Shop Orders 
        Route::get('/shopOrders', [OrderController::class, 'shopOrders'])->name('shopOrders');

        // Meal Management
        Route::post('/meals', [MealController::class, 'store'])->name('meals.store');
        Route::put('/meals/{id}', [MealController::class, 'update'])->name('meals.update');
        Route::delete('/meals/{id}', [MealController::class, 'destroy'])->name('meals.destroy');

        // Meal Image Management
        Route::get('/meals/{meal}/images', [MealController::class, 'getMealImages'])
            ->name('meals.images.get');
        Route::delete('/meals/{meal}/images/{image}', [MealController::class, 'deleteImage'])
            ->name('meals.images.delete');
        Route::post('/meals/{meal}/images/{image}/set-primary', [MealController::class, 'setPrimaryImage'])
            ->name('meals.images.setPrimary');

        // ===== MEAL OPTION MANAGEMENT =====
        // Option Groups
        Route::post('/meals/{meal}/options/groups', [MealOptionController::class, 'storeGroup'])
            ->name('meal.options.groups.store');
        Route::put('/options/groups/{group}', [MealOptionController::class, 'updateGroup'])
            ->name('meal.options.groups.update');
        Route::delete('/options/groups/{group}', [MealOptionController::class, 'destroyGroup'])
            ->name('meal.options.groups.destroy'); // ...

        // Option Values
        Route::post('/options/groups/{group}/values', [MealOptionController::class, 'storeValue'])
            ->name('meal.options.values.store');
        Route::put('/options/values/{value}', [MealOptionController::class, 'updateValue'])
            ->name('meal.options.values.update');
        Route::delete('/options/values/{value}', [MealOptionController::class, 'destroyValue'])
            ->name('meal.options.values.destroy');

        // Get meal options
        Route::get('/meals/{meal}/options', [MealOptionController::class, 'getMealOptions'])
            ->name('meal.options.get');

        // Order Scan (QR)
        Route::post('/orders/scan-check', [OrderController::class, 'checkOrderViaQr'])
            ->name('orders.scan-check');
        Route::post('/orders/scan-completion', [OrderController::class, 'completeOrderViaQr'])
            ->name('orders.scan-completion');
    });

    // Shop Cancel Request
    Route::delete('/shops/cancel', [ShopController::class, 'cancelRequest'])->name('shops.cancel');

});


// ===== OWNER ONLY ROUTES =====
Route::middleware(['auth', OwnerMiddleware::class])->group(function () {
    // Shop must be approved
    Route::middleware([ShopApprovedMiddleware::class])->group(function () {
        
        // Analytics Dashboard
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    
        // Shop Overview (analytics)
        Route::get('/owner/analytics', [AnalyticsController::class, 'ownerIndex'])->name('owner.analytics');
    
        // Manage Staff
        Route::post('/shops/{shop}/staff', [ShopController::class, 'addStaff'])->name('shops.staff.add');
        Route::delete('/shops/{shop}/staff/{user}', [ShopController::class, 'removeStaff'])->name('shops.staff.remove');
        
        // API for staff_notification toggle
        Route::patch('/shops/{shop}/staff/{user}/notification', [ShopController::class, 'updateStaffNotification'])->name('shops.staff.notification');
    });
});


// Midtrans Webhook
Route::post('/midtrans/webhook', [MidtransController::class, 'handleNotification']);

// Set Timezone
Route::post('/timezone', function (Request $request) {
    if ($request->timezone) {
        session(['timezone' => $request->timezone]);
    }
});


// Test route
Route::get('test', function () {
    $data = \App\Models\Meal::all();
    return view('tes', compact('data'));
});