<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_date',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'shipping_address',
        'shipping_fee',
        'note'
    ];

    protected $casts = [
    'order_date' => 'datetime',
    ];
    // Mỗi đơn hàng thuộc về 1 người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotal()
    {
    $orderItemsTotal = $this->orderItems->sum(function($item) {
        return $item->quantity * $item->price;
    });
    return $orderItemsTotal + $this->shipping_fee;
    }
}
