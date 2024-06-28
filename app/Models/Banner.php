<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'image',
        'title',
        'text',
        'priority',
        'is_active',
        'type',
        'button_text',
        'button_link',
        'button_icon'
    ];
}
