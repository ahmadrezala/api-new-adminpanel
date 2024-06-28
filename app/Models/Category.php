<?php

namespace App\Models;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = [
        'name',
        'image',
        'slug',
        'description',
        'parent_id',
        'attribute_ids',
        'attribute_is_filter_ids',
        'variation_id'
    ];



    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_category');
    }
}
