<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Meal;

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
}