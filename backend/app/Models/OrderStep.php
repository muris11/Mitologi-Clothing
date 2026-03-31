<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStep extends Model
{
    protected $fillable = [
        'step_number',
        'title',
        'description',
        'type',
        'sort_order',
    ];
}
