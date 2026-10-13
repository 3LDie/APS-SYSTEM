<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = ['order_id', 'delivered_by', 'delivery_timestamp', 'proof_of_delivery_path', 'notes'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}