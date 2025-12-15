<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class OrderItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'option_name',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}