<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintingMethod extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'image',
    'pros',
    'price_range',
    'is_active',
    'sort_order',
  ];

  protected $casts = [
    'pros' => 'array',
    'is_active' => 'boolean',
    'sort_order' => 'integer',
  ];
}
