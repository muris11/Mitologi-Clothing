<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = ['order_id', 'name', 'phone', 'address', 'city', 'province', 'postal_code'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
