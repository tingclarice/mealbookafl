<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class GowaController extends Controller
{

    // by userID
    // public static function sendMessage(string $message, int $userId)
    // {
    //     // Example: get phone number from user
    //     $user = User::findOrFail($userId);

    //     $response = Http::withBasicAuth(
    //         config('services.gowa.username'),
    //         config('services.gowa.password')
    //     )
    //         ->post('https://gowa.felitech.site/send/message', [
    //             'phone' => $user->phone,
    //             'message' => $message,
    //         ]);

    //     if ($response->failed()) {
    //         \Log::error('Gowa WhatsApp failed', [
    //             'response' => $response->body()
    //         ]);

    //         return false;
    //     }

    //     return true;
    // }

    // send message by message and phone
    public static function sendMessage(string $message, int $phone)
    {
        $phone = (string) $phone;
        $response = Http::withBasicAuth(
            config('services.gowa.username'),
            config('services.gowa.password')
        )
            ->post('https://gowa.felitech.site/send/message', [
                'phone' => $phone,
                'message' => $message,
            ]);

        if ($response->failed()) {
            \Log::error('Gowa WhatsApp failed', [
                'response' => $response->body()
            ]);

            return false;
        }

        return true;
    }

}
