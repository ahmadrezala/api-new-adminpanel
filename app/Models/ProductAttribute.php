<?php

namespace App\Models;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';
    protected $guarded = [];



    public function products()
    {
        return $this->belongsTo(Product::class, ' product_attributes');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
