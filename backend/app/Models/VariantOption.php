<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    protected $fillable = ['variant_id', 'name', 'value'];
    public $timestamps = false;

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
