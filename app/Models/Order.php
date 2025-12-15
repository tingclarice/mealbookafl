<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'order_status',
        'total_amount',
        'scheduled_pickup',
        'qr_code'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'scheduled_pickup' => 'datetime'
    ];

    // Automatically generate QR code when order is created
    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->qr_code)) {
                $order->qr_code = 'ORD-' . strtoupper(Str::random(12));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    // Generate QR code SVG
    public function getQrCodeSvgAttribute()
    {
        return QrCode::size(300)->generate($this->qr_code);
    }
}
