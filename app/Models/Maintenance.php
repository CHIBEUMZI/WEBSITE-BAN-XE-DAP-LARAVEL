<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
        'email',
        'product_id',
        'product_sku',
        'issue_description',
        'preferred_date',
        'address',
        'status',
        'employee_id',
    ];
    protected $casts = [
    'preferred_date' => 'date',
    ];
    /**
     * Người dùng gửi yêu cầu bảo trì
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sản phẩm cần bảo trì
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Nhân viên được giao xử lý
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
