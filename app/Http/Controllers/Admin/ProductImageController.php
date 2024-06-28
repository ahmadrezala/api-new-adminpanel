<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Repository\ProductImageRepository;
use App\Http\ApiRequests\Admin\Product\ProductImageUpdateApiRequest;
use App\Http\Resources\Admin\ProductResource\ProductImageListApiResource;
use App\Models\Product;

class ProductImageController extends Controller
{



    public function __construct(private ProductImageRepository $ProductImageRepository)
    {
    }

    // public function index()
    // {
    //     $result = $this->ProductImageRepository->getAllProductImages();
    //     return $result->ok
    //         ? ApiResponse::withData(ProductImageListApiResource::collection($result->data)->resource)->build()->response()
    //         : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    // }

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



}
