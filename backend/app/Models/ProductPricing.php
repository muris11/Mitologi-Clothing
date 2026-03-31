<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
  use HasFactory;

  protected $fillable = [
    'category_name',
    'items',
    'min_order',
    'notes',
    'is_active',
    'sort_order',
  ];

  protected $casts = [
    'items' => 'array',
    'is_active' => 'boolean',
    'sort_order' => 'integer',
  ];
}
