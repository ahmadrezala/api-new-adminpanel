<?php

namespace App\Repository;

use App\Models\Product;
use App\Models\Category;
use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;

class ProductRepository
{

    public function __construct(public Product $product)
    {

    }



    public function createProduct(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {


            $inputs['images'] && $images = uploadimages($inputs['images'], 'product');
            $inputs['primary_image'] && $image = uploadimages($inputs['primary_image'], 'product');
            $product = $this->product->create([
                'name' => $inputs['name'],
                'slug' => $inputs['slug'],
                'brand_id' => $inputs['brand_id'],
                'category_id' => $inputs['category_id'],
                'primary_image' => $image,
                'description' => $inputs['description'],
                'is_active' => $inputs['is_active'],
                'delivery_amount' => $inputs['delivery_amount'],
                'delivery_amount_per_product' => $inputs['delivery_amount_per_product'],
            ]);



            $this->createProductImages($images, $product);

            $this->createProductAttributes($product, $inputs['attribute_ids']);

            $this->createProductVariations($inputs, $product);

            $product->tags()->attach($inputs['tag_ids']);





        });

    }
    public function updateProduct(array $inputs, $product): ServiceResult
    {


        return app(ServiceWrapper::class)(function () use ($inputs, $product) {

            $product->update([
                'name' => $inputs['name'],
                'brand_id' => $inputs['brand_id'],
                'description' => $inputs['description'],
                'is_active' => $inputs['is_active'],
                'delivery_amount' => $inputs['delivery_amount'],
                'delivery_amount_per_product' => $inputs['delivery_amount_per_product'],
            ]);



            $this->updateProductAttributes($inputs['attribute_ids']);
            $this->updateProductVariations($inputs['variation_values']);




        });

    }
    public function deleteProduct($product): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($product) {




        });

    }


    public function getAllProducts(): ServiceResult
    {
        return app(ServiceWrapper::class)(function () {
            return $this->product->orderBy('id', 'desc')->paginate(9);
        });
    }

    public function createProductImages($images, $product)
    {
        foreach ($images as $image) {
            $product->images()->create(['image' => $image]);
        }
    }


    public function createProductAttributes($product, $attributeIds)
    {



        foreach ($attributeIds as $attributeId => $value) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $attributeId,
                'value' => $value
            ]);
        }
    }


    public function updateProductAttributes($attributes)
    {
        // dd($attributes);
        foreach ($attributes as $id => $value) {
            $productAttibute = ProductAttribute::findOrFail($id);

            $productAttibute->update([
                'value' => $value
            ]);
        }

    }

    public function updateProductVariations($variations)
    {

        foreach ($variations as $id => $value) {
            $productVariation = ProductVariation::findOrFail($id);

            $productVariation->update([
                'price' => $value['price'],
                'quantity' => $value['quantity'],
                'sku' => $value['sku'],
                'sale_price' => $value['sale_price'],
                'date_on_sale_from' => $value['date_on_sale_from'],
                'date_on_sale_to' => $value['date_on_sale_to'],
            ]);
        }
    }




    public function createProductVariations($inputs, $product)
    {


        $counter = count($inputs['variation_values']['value']);

        $category = Category::find($inputs['category_id']);
        $attributeId = $category->attributes()->wherePivot('is_variation', '1')->first()->id;

        for ($i = 0; $i < $counter; $i++) {
            ProductVariation::create([
                'attribute_id' => $attributeId,
                'product_id' => $product->id,
                'value' => $inputs['variation_values']['value'][$i],
                'price' => $inputs['variation_values']['price'][$i],
                'quantity' => $inputs['variation_values']['quantity'][$i],
                'sku' => $inputs['variation_values']['sku'][$i]
            ]);
        }
    }


}
