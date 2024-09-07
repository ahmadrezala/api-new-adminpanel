<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Repository\ProductCategoryRepository;
use App\Http\Resources\Admin\ProductResource\CategoryListApiResource;
use App\Http\ApiRequests\Admin\Product\ProductCategoryUpdateApiRequest;
use App\Http\Resources\Admin\ProductResource\ProductCategoryApiResource;

class ProductCategoryController extends Controller
{

    public function __construct(private ProductCategoryRepository $ProductCategoryRepository)
    {
    }

    public function productCategory(Product $product)
    {

        return ApiResponse::withData(new ProductCategoryApiResource($product))->build()->response();


    }

    public function categories()
    {

        $result = $this->ProductCategoryRepository->getAllCategories();

        return $result->ok
            ? ApiResponse::withData(CategoryListApiResource::collection($result->data))->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }


    public function updateCategory(ProductCategoryUpdateApiRequest $request, Product $product)
    {
     


        $result = $this->ProductCategoryRepository->updateCategory($request->validated(), $product);
        return $result->ok
            ? ApiResponse::withMessage('ProductCategory create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }
}
