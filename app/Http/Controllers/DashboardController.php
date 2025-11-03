<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboardMeal(){
        return view('dashboard.menuDashboard');
    }

    function dashboardUser(){
        return view('dashboard.userDashboard');
    }
}
