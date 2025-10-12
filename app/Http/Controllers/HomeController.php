<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class HomeController extends Controller
{
    public function index()
    {
        $meals = Meal::available()->take(6)->get();
        return view('home', compact('meals'));
    }
}
