<?php

namespace App\Repository;

use App\Models\Category;
use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;

class ProductCategoryRepository
{

    public function __construct(private Category $category)
    {

    }



    public function getAllCategories()
    {


        return app(ServiceWrapper::class)(function () {

            return $this->category->all();
           

        });

    }





    public function updateCategory(array $inputs, $product): ServiceResult
    {


        return app(ServiceWrapper::class)(function () use ($inputs, $product) {

            $product->update([
                'category_id' => $inputs['category_id']
            ]);

            $this->updateProductAttribute($inputs['attribute_ids'], $product);

            $this->updateProductVariation($inputs['variation_values'], $product, $inputs['category_id']);




        });

    }



    public function updateProductAttribute($attributes, $product)
    {

        ProductAttribute::where('product_id', $product->id)->delete();

        foreach ($attributes as $key => $value) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $key,
                'value' => $value
            ]);
        }
    }




    public function updateProductVariation($variations, $product, $category_id)
    {
        ProductVariation::where('product_id', $product->id)->delete();
        $category = Category::find($category_id);
        $attributeId = $category->attributes()->wherePivot('is_variation', 1)->first()->id;

        $counter = count($variations['value']);
        for ($i = 0; $i < $counter; $i++) {
            ProductVariation::create([
                'attribute_id' => $attributeId,
                'product_id' => $product->id,
                'value' => $variations['value'][$i],
                'price' => $variations['price'][$i],
                'quantity' => $variations['quantity'][$i],
                'sku' => $variations['sku'][$i]
            ]);
        }
    }


}
