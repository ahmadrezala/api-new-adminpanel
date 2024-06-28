<?php

namespace App\Repository;

use App\Models\Category;
use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use Illuminate\Support\Facades\File;

class CategoryRepository
{


    public function __construct(private Category $category)
    {



    }




    public function createCategory(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {


            if ($inputs['image'])
                $image = uploadimages($inputs['image'], 'category');

            $category = $this->category->create([
                'name' => $inputs['name'],
                'slug' => $inputs['slug'],
                'image' => $image,
                'description' => $inputs['description'],

            ]);

            $this->syncCategoryAttributes($category, $inputs);

        });

    }






    public function updateCategory(array $inputs, $category): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $category) {

            if ($inputs['image']) {

                // delete old image
                $this->handleImageDelete($category);
                //  upload new image
                $image = uploadimages($inputs['image'], 'category');
            }

            $category->update([
                'name' => $inputs['name'],
                'slug' => $inputs['slug'],
                'image' => $image,
                'description' => $inputs['description'],

            ]);


            $this->syncCategoryAttributes($category, $inputs, 'PUT');


        });

    }



    public function syncCategoryAttributes($category, $inputs, $method = 'POST')
    {

        $attributeIds = $inputs['attribute_ids'] ?? [];
        $filterIds = $inputs['attribute_is_filter_ids'] ?? [];
        $variationId = $inputs['variation_id'] ?? null;

        if ($method === 'PUT')

            $category->attributes()->detach();

        foreach ($attributeIds as $attributeId) {
            $category->attributes()->attach($attributeId, [
                'is_filter' => in_array($attributeId, $filterIds) ? 1 : 0,
                'is_variation' => $variationId == $attributeId ? 1 : 0
            ]);
        }


    }


    public function deleteCategory($Category): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($Category) {
            $this->handleImageDelete($Category);
            return $Category->delete();
        });

    }


    public function getAllCategorys(): ServiceResult
    {
        return app(ServiceWrapper::class)(function () {
            return $this->category->orderBy('id', 'desc')->paginate(9);
        });
    }



    public function handleImageDelete($category)
    {
        if (!empty($category->image)) {
            $imagePath = public_path($category->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }


}
