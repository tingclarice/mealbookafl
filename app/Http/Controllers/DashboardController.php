<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboardMeal(){
        return view('dashboard.menuDashboard');
    }

    function dashboardUsers(){
        $users = User::all();
        return view('dashboard.userDashboard', compact('users'));
    }
}
