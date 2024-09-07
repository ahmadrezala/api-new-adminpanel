<?php

namespace App\Http\Controllers\Admin;


use App\Models\Brand;
use Illuminate\Http\Request;
use App\Repository\BrandRepository;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Brand\BrandStoreApiRequest;
use App\Http\ApiRequests\Admin\Brand\BrandUpadateApiRequest;
use App\Http\Resources\Admin\BrandResource\BrandsListApiResource;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(private BrandRepository $brandRepository)
    {
    }




    public function index(Request $request)
    {

        $result = $this->brandRepository->getAllBrands($request->query('search'));
        return $result->ok
            ? ApiResponse::withData([
                'brands' => BrandsListApiResource::collection($result->data)->response()->getData()->data,
                'total_pages' => BrandsListApiResource::collection($result->data)->response()->getData()->meta->last_page,
                'current_page' => BrandsListApiResource::collection($result->data)->response()->getData()->meta->current_page,

            ])->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreApiRequest $request)
    {


        $result = $this->brandRepository->createBrand($request->validated());
        return $result->ok
            ? ApiResponse::withData(new BrandsListApiResource($result->data))->withMessage('brand create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(BrandUpadateApiRequest $request, Brand $brand)
    {

        $result = $this->brandRepository->updateBrand($request->validated(), $brand);
        return $result->ok
            ? ApiResponse::withData($result->data)->withMessage('brand updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    public function show(Brand $brand)
    {

        return ApiResponse::withData($brand)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $result = $this->brandRepository->deleteBrand($brand);
        return $result->ok
            ? ApiResponse::withMessage('brand deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
