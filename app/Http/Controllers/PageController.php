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
    public function editProfile(Request $request){
        return view('settings', [
            'user' => $request->user(),
        ]);
    }

    // Update Settings
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroyProfile(Request $request){
        $user = $request->user();

        if (strtolower($request->input('confirm_text')) !== 'delete my account') {
            return back()->withErrors([
                'confirm_text' => 'You must type "delete my account" to confirm.',
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }

}
