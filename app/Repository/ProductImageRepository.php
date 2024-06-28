<?php

namespace App\Repository;

use App\Base\ServiceResult;
use App\Base\ServiceWrapper;

class ProductImageRepository
{

    public function __construct()
    {

    }



    public function updateImages(array $inputs, $product): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $product) {


            if (isset ($inputs['primary_image'])) {

                $this->handleImageDelete($product);

                $image = uploadimages($inputs['primary_image'], 'product');

                $product->update([
                    'primary_image' => $image
                ]);

            }

            if (isset ($inputs['images'])) {

                $this->handleImagesDelete($product);

                $images = uploadimages($inputs['images'], 'product');

                $product->images()->delete();

                foreach ($images as $image) {
                    $product->images()->create(['image' => $image]);
                }
            }



        });

    }



    public function handleImageDelete($product)
    {
        if (!empty($product->primary_image)) {
            $imagePath = public_path($product->primary_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


    }
    public function handleImagesDelete($product)
    {
        $images = $product->images()->get()->pluck('image')->toArray();

        if (!empty($images)) {
            foreach ($images as $image) {
                $imagePath = public_path($image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

        }



    }


}
