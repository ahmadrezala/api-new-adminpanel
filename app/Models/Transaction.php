<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    // protected $fillable = [
    //     'user_id',
    //     'order_id',
    //     'amount',
    //     'ref_id',
    //     'token',
    //     'description',
    //     'gateway_name',
    //     'status',
    // ];

    protected $guarded = [];
}
