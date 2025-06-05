<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'original_price',
        'price',
        'stock',
        'brand',
        'sku',
        'discount',
        'description',
        'image',
    ];
    
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

}
