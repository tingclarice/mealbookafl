<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
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
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->setHttpClient(new Client(['verify' => base_path('certs/cacert.pem')]))
            ->user();

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
    }
}
