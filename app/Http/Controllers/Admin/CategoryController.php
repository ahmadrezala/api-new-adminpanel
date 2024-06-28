<?php

namespace App\Http\Controllers\Admin;


use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Repository\CategoryRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Category\CategoryStoreApiRequest;
use App\Http\ApiRequests\Admin\Category\CategoryUpdateApiRequest;
use App\Http\Resources\Admin\CategoryResource\CategoryListApiResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(private CategoryRepository $categoryRepository)
    {
    }




    public function index()
    {
        $result = $this->categoryRepository->getAllCategorys();
        return $result->ok
            ? ApiResponse::withData(CategoryListApiResource::collection($result->data)->resource)->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreApiRequest $request)
    {

        $result = $this->categoryRepository->createCategory($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('Category create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateApiRequest $request, Category $Category)
    {
        $result = $this->categoryRepository->updateCategory($request->validated(), $Category);
        return $result->ok
            ? ApiResponse::withMessage('Category updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $Category)
    {
        $result = $this->categoryRepository->deleteCategory($Category);
        return $result->ok
            ? ApiResponse::withMessage('Category deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
