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

    public function handleNotification(Request $request)
    {
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

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

        $payload = $request->all();

        // 4. Find the Order
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

            // case 'cancel':
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
