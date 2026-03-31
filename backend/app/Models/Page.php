<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'handle', 'body', 'body_summary', 'seo_title', 'seo_description'];
}
