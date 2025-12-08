<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PageController extends Controller
{
    // Landing page
    public function index(){
        $meals = Meal::where('isAvailable', true)->take(6)->get();
        return view('home', compact('meals'));
    }

    // About Page
    public function about(){
        return view('about');
    }


    // Profile Settings
    public function settings(Request $request){
        $pendingOwnedShop = Auth::user()->shops()
                                        ->wherePivot('role', 'OWNER')
                                        ->whereIn('status', ['PENDING', 'REJECTED'])
                                        ->first();
        $user = $request->user();
        return view('settings', compact('user', 'pendingOwnedShop'));
    }

    

}
