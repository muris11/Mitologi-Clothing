<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigAuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'group',
        'key',
        'old_value',
        'new_value',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
