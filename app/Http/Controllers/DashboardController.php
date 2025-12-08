<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboardMeal(Request $request){
        // $meals = Meal::orderBy('created_at', 'desc')->get();
        $meals = $request->user()->meals()->get();

        return view('dashboard.menuDashboard', compact('meals'));
    }

    function dashboardUsers(){
        $users = User::all();
        return view('dashboard.userDashboard', compact('users'));
    }
}