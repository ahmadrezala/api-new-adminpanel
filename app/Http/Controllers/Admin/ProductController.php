<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\ProductRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Product\ProductStoreApiRequest;
use App\Http\ApiRequests\Admin\Product\ProductUpdateApiRequest;
use App\Http\Resources\Admin\ProductResource\ProductApiResource;
use App\Http\Resources\Admin\ProductResource\BrandListApiResource;
use App\Http\Resources\Admin\ProductResource\ProductListApiResource;
use App\Http\Resources\Admin\ProductResource\CategoryListApiResource;
use App\Http\Resources\Admin\ProductResource\AttributescategoryListApiResource;

class ProductController extends Controller
{


    public function __construct(private ProductRepository $productRepository)
    {
    }




    public function index(Request $request)
    {
        $result = $this->productRepository->getAllProducts($request->query('search'), $request->query('count'));
        return $result->ok
            ? ApiResponse::withData([
                'products' => productListApiResource::collection($result->data)->response()->getData()->data,
                'total_pages' => productListApiResource::collection($result->data)->response()->getData()->meta->last_page,
                'current_page' => productListApiResource::collection($result->data)->response()->getData()->meta->current_page,

            ])->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }



    public function create()
    {
        $resultBrands = $this->productRepository->getAllBrands();
        $resultCategories = $this->productRepository->getAllCategories();
        $resultTags = $this->productRepository->getAllTags();

        return $resultBrands->ok && $resultCategories->ok && $resultTags->ok
            ? ApiResponse::withData([
                'brands' => BrandListApiResource::collection($resultBrands->data)->resource,
                'categories' => CategoryListApiResource::collection($resultCategories->data)->resource,
                'tags' => CategoryListApiResource::collection($resultTags->data)->resource,

            ])->build()->response()

            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }


    public function attributesCategory(Category $category)
    {
        return ApiResponse::withData(new AttributescategoryListApiResource($category))->build()->response();

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







    public function edit(Product $product)
    {

        return ApiResponse::withData(new ProductApiResource($product))->build()->response();
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
