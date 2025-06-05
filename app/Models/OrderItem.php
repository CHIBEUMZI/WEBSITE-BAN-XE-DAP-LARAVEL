<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu không theo convention
    protected $table = 'order_items';

    // Đặt các cột có thể mass-assigned
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price',
    ];

    /**
     * Mối quan hệ với bảng `orders`: Mỗi `OrderItem` thuộc về một `Order`
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mối quan hệ với bảng `products`: Mỗi `OrderItem` liên kết với một `Product`
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
