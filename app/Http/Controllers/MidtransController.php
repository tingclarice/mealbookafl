<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransController extends Controller
{
    // public function handleNotification(Request $request)
    // {
    //     $payload = $request->all();

    //     Log::info('\n\n Midtrans Notification \n requested payload : \n', $payload);

    //     $serverKey = config('midtrans.server_key');

    //     // Create the hash: order_id + status_code + gross_amount + ServerKey
    //     $hashed = hash(
    //         'sha512',
    //         $payload['order_id'] .
    //         $payload['status_code'] .
    //         $payload['gross_amount'] .
    //         $serverKey
    //     );

    //     // Compare your hash with the one Midtrans sent
    //     if ($hashed !== $payload['signature_key']) {
    //         Log::warning("Midtrans Webhook Signature Mismatch!");
    //         return response()->json(['message' => 'Invalid Signature'], 403);
    //     }

    //     $order = Order::where('midtrans_order_id', $payload['order_id'])->firstOrFail();
    //     if (!$order) {
    //         Log::error("Webhook received for unknown Order ID: " . $payload['order_id']);
    //         return response()->json(['message' => 'Order not found'], 404);
    //     }

    //     switch ($payload['transaction_status']) {
    //         case 'settlement':
    //         case 'capture':
    //             $order->markAsPaid($payload);
    //             break;

    //         case 'pending':
    //             $order->markAsPending($payload);
    //             break;

    //         case 'expire':
    //             $order->markAsExpired($payload);
    //             break;

    //         case 'cancel':
    //         case 'deny':
    //             $order->markAsFailed($payload);
    //             break;
    //     }

    //     return response()->json(['status' => 'ok']);
    // }

    public function handleNotification(Request $request)
    {
        try {
            // 1. Initialize Config 
            // (REQUIRED so the library knows your Server Key to verify the signature)
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // 2. Create Notification Instance
            // This AUTOMATICALLY reads php://input and verifies the SHA512 signature.
            // If the signature is fake/invalid, this line throws an Exception.
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error("Midtrans Error: " . $e->getMessage());
            return response()->json(['message' => 'Invalid Signature or Request'], 403);
        }

        // 3. Get key variables from the Midtrans object
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // We keep $payload for your model methods (markAsPaid, etc.) 
        // to preserve your existing logic that expects an array.
        $payload = $request->all();

        // 4. Find the Order
        // We use first() instead of firstOrFail() so we can return a proper JSON error 
        // if it's missing, rather than a Laravel 404 HTML page.
        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            Log::error("Webhook received for unknown Order ID: " . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 5. Handle Status using the $notif object properties
        switch ($transaction) {
            case 'capture':
                // For Credit Cards, we should check fraud status
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->markAsPending($payload);
                    } else {
                        $order->markAsPaid($payload);
                    }
                } else {
                    // Fallback for non-CC captures (rare)
                    $order->markAsPaid($payload);
                }
                break;

            case 'settlement':
                $order->markAsPaid($payload);
                break;

            case 'pending':
                $order->markAsPending($payload);
                break;

            case 'expire':
                $order->markAsExpired($payload);
                break;

            case 'cancel':
            case 'deny':
                $order->markAsFailed($payload);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    public function snapToken(Order $order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->midtrans_order_id,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $order->update([
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'snap_token' => $snapToken,
        ]);
    }
}
