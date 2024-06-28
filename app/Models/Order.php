<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'address_id',
        'coupon_id',
        'status',
        'total_amount',
        'delivery_amount',
        'coupon_name',
        'paying_amount',
        'payment_type',
        'payment_status',
        'description',
    ];


    public function  orderItems()
    {
       return $this->hasMany(OrderItem::class);
    }
}
