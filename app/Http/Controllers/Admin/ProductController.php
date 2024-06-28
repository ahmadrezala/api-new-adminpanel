<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Repository\ProductRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Product\ProductStoreApiRequest;
use App\Http\ApiRequests\Admin\Product\ProductUpdateApiRequest;
use App\Http\Resources\Admin\ProductResource\ProductListApiResource;

class ProductController extends Controller
{


    public function __construct(private ProductRepository $productRepository)
    {
    }




    public function index()
    {
        $result = $this->productRepository->getAllProducts();
        return $result->ok
            ? ApiResponse::withData(ProductListApiResource::collection($result->data)->resource)->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreApiRequest $request)
    {
        

        $result = $this->productRepository->createProduct($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('Product create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateApiRequest $request, Product $product)
    {
        $result = $this->productRepository->updateProduct($request->validated(), $product);
        return $result->ok
            ? ApiResponse::withMessage('Product updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $result = $this->productRepository->deleteProduct($product);
        return $result->ok
            ? ApiResponse::withMessage('Product deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
