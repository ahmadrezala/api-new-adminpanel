<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Repository\ProductCategoryRepository;
use App\Http\ApiRequests\Admin\Product\ProductCategoryUpdateApiRequest;

class ProductCategoryController extends Controller
{

    public function __construct(private ProductCategoryRepository $ProductCategoryRepository)
    {
    }


    public function updateCategory(ProductCategoryUpdateApiRequest $request, Product $product)
    {


        $result = $this->ProductCategoryRepository->updateCategory($request->validated() , $product);
        return $result->ok
            ? ApiResponse::withMessage('ProductCategory create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }
}
