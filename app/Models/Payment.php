<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_provider',
        'payment_method',
        'provider_transaction_id',
        'provider_reference_id',
        'amount',
        'status',
        'va_number',
        'qr_string',
        'ewallet_type',
        'expiry_time',
        'paid_at',
        'raw_request',
        'raw_callback'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expiry_time' => 'datetime',
        'paid_at' => 'datetime',
        'raw_request' => 'array',
        'raw_callback' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}