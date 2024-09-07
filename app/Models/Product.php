<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products';
    protected $fillable = [
        'name',
        'brand_id',
        'category_id',
        'slug',
        'primary_image',
        'description',
        'status',
        'is_active',
        'count_visit ',
        'count_sale',
        'delivery_amount',
        'delivery_amount_per_product',
    ];

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
