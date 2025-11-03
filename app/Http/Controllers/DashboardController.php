<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Meal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboardMeal(){
        $meals = Meal::orderBy('created_at', 'desc')->get();
        return view('dashboard.menuDashboard', compact('meals'));
    }

    function dashboardUser(){
        return view('dashboard.userDashboard');
    }
}