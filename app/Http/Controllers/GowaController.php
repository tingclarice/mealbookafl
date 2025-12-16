<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class GowaController extends Controller
{
    public static function sendMessage(string $message, int $userId)
    {
        // Example: get phone number from user
        $user = User::findOrFail($userId);

        $response = Http::withBasicAuth(
            config('services.gowa.username'),
            config('services.gowa.password')
        )
            ->post('https://gowa.felitech.site/send/message', [
                'phone' => $user->phone,
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
