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

        Log::info('Midtrans Notification', $payload);

        $order = Order::where('midtrans_order_id', $payload['order_id'])->firstOrFail();

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
