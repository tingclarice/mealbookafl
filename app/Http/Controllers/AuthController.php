<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Google Login
    function loginGoogle(){
        return Socialite::driver('google')->redirect();
    }

    function handleGoogleResponse(){
        try {
            // Modern Socialite - just remove stateless() and setHttpClient()
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(str()->random(16)),
                ]
            );

            Auth::login($user);
            return redirect('/');
            
        } catch (\Exception $e) {
            logger()->error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Unable to login with Google. Please try again.');
        }
    }

    // Show Register Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle Manual Registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Auth::login($user);

        // Redirect ke halaman home biar konsisten sama Google login
        return redirect('/')->with('success', 'Welcome to MealBook!');
    }


    // Logout (works for both regular auth & Google OAuth)
    function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}