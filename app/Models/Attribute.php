<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;

    protected $table = "attributes";
    protected $fillable = [
        'name'
    ];



    public function categories()
    {
        return $this->belongsToMany(Category::class , 'attribute_category');
    }


    public function values()
    {
        return $this->hasMany(ProductAttribute::class)->select('attribute_id' , 'value')->distinct();
    }
}
