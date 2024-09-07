<?php

namespace App\Repository;

use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use App\Models\ProductImage;

class ProductImageRepository
{

    public function __construct()
    {

    }



    public function updateImages(array $inputs, $product): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $product) {


            if (isset($inputs['primary_image'])) {

                $this->handleImageDelete($product);

                $image = uploadimages($inputs['primary_image'], 'product');

                $product->update([
                    'primary_image' => $image
                ]);

            }

            if (isset($inputs['images'])) {

                $this->handleImagesDelete($product);

                $images = uploadimages($inputs['images'], 'product');

                $product->images()->delete();

                foreach ($images as $image) {
                    $product->images()->create(['image' => $image]);
                }
            }



        });

    }



    public function deleteImages($productImage): ServiceResult
    {
      
        return app(ServiceWrapper::class)(function () use ($productImage) {
            $this->handelDeleteProductImages($productImage);
            return $productImage->delete();
        });

    }


    public function handelDeleteProductImages($productImage)
    {

        if (!empty($productImage->image)) {
            $imagePath = public_path($productImage->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


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
