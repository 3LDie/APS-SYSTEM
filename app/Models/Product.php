<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['variety_name', 'style_type', 'stock_quantity', 'unit_price', 'reorder_level'];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}