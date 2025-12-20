<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        $payload = $request->all();

        Log::info('\n\n Midtrans Notification \n requested payload : \n', $payload);

        // Log::channel('daily')->info('MIDTRANS HIT', [
        //     'headers' => $request->headers->all(),
        //     'body' => $request->getContent(),
        // ]);

        $serverKey = config('services.midtrans.server_key');
        Log::info("");
        Log::info("server key : ", $serverKey);

        // Create the hash: order_id + status_code + gross_amount + ServerKey
        $hashed = hash(
            'sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );
        Log::info('\n\ninternal signature key : ' . $hashed);

        // Compare your hash with the one Midtrans sent
        if ($hashed !== $payload['signature_key']) {
            Log::warning("Midtrans Webhook Signature Mismatch!");
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $order = Order::where('midtrans_order_id', $payload['order_id'])->firstOrFail();
        if (!$order) {
            Log::error("Webhook received for unknown Order ID: " . $payload['order_id']);
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($payload['transaction_status']) {
            case 'settlement':
            case 'capture':
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
