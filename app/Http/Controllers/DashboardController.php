<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Meal;
use App\Models\Shop;

class DashboardController extends Controller
{

    public function dashboardMeal(){
        $meals = Meal::orderBy('created_at', 'desc')->get();
        return view('dashboard.menuDashboard', compact('meals'));
    }

    function dashboardUsers(){
        $users = User::all();
        return view('dashboard.userDashboard', compact('users'));
    }

    public function dashboardShop(){
        // can be modified to handle multiple shops per user
        $shop = Shop::with(['wallet', 'meals', 'userRoles.user'])->first();
        
        // If no shop exists yet
        if (!$shop) {
            return view('dashboard.shopDashboard', [
                'shop' => null,
                'message' => 'No shop found. Please create a shop first.'
            ]);
        }
        
        return view('dashboard.shopDashboard', compact('shop'));
    }
}