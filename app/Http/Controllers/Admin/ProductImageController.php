<?php

namespace App\Http\Controllers\Admin;


use App\Models\Product;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Repository\ProductImageRepository;
use App\Http\ApiRequests\Admin\Product\ProductImageUpdateApiRequest;
use App\Http\Resources\Admin\ProductResource\ProductImageApiResource;
use App\Http\Resources\Admin\ProductResource\ProductImageListApiResource;
use App\Models\ProductImage;

class ProductImageController extends Controller
{



    public function __construct(private ProductImageRepository $ProductImageRepository)
    {
    }

    public function showImages(Product $product)
    {

           return  ApiResponse::withData(new ProductImageApiResource($product))->build()->response();



    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateImages(ProductImageUpdateApiRequest $request, Product $product)
    {


        $result = $this->ProductImageRepository->updateImages($request->validated() , $product);
        return $result->ok
            ? ApiResponse::withMessage('ProductImage create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    public function destroyImage(ProductImage $productImage)
    {
       
        $result = $this->ProductImageRepository->deleteImages($productImage);
        return $result->ok
            ? ApiResponse::withMessage('Category deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }



}
