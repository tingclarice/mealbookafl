<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        $meals = Meal::where('isAvailable', true)->take(6)->get();
        return view('home', compact('meals'));
    }

    public function about(){
        return view('about');
    }

    public function settings(){
        return view('settings');
    }

}
