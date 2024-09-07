<?php

namespace App\Http\Controllers\Admin;


use App\Http\Resources\Admin\CategoryResource\CategoryApiResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\CategoryRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Category\CategoryStoreApiRequest;
use App\Http\ApiRequests\Admin\Category\CategoryUpdateApiRequest;
use App\Http\Resources\Admin\CategoryResource\CategoryListApiResource;
use App\Http\Resources\Admin\CategoryResource\AttributeListApiResource;
use App\Http\Resources\Admin\CategoryResource\CategoryParentListApiResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(private CategoryRepository $categoryRepository)
    {
    }




    public function index(Request $request)
    {
        $result = $this->categoryRepository->getAllCategories($request->query('search'), $request->query('count'));
        return $result->ok
            ? ApiResponse::withData([
                'categories' => CategoryListApiResource::collection($result->data)->response()->getData()->data,
                'total_pages' => CategoryListApiResource::collection($result->data)->response()->getData()->meta->last_page,
                'current_page' => CategoryListApiResource::collection($result->data)->response()->getData()->meta->current_page,

            ])->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    public function create()
    {
        $resultAttributes = $this->categoryRepository->getAllAttributes();
        $resultCategories = $this->categoryRepository->getParentCategories();

        return $resultAttributes->ok && $resultCategories->ok
            ? ApiResponse::withData([
                'attributes' => AttributeListApiResource::collection($resultAttributes->data)->resource,
                'categories' => CategoryParentListApiResource::collection($resultCategories->data)->resource


            ])->build()->response()

            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreApiRequest $request)
    {
        // dd($request->validated());

        $result = $this->categoryRepository->createCategory($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('Category create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateApiRequest $request, Category $category)
    {

        $result = $this->categoryRepository->updateCategory($request->validated(), $category);
        return $result->ok
            ? ApiResponse::withMessage('Category updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }



    public function show(Category $category)
    {

        return ApiResponse::withData(new CategoryApiResource($category))->build()->response();
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
