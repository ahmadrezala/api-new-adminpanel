<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\BannerRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Banner\BannerStoreApiRequest;
use App\Http\ApiRequests\Admin\Banner\BannerUpdateApiRequest;
use App\Http\Resources\Admin\BannerResource\BannerListApiResource;
use App\Http\Resources\Admin\BannerResource\BannerShowApiResource;

class BannerController extends Controller
{
    public function __construct(private BannerRepository $BannerRepository)
    {
    }




    public function index()
    {
        $result = $this->BannerRepository->getAllBanners();
        return $result->ok
            ? ApiResponse::withData(BannerListApiResource::collection($result->data)->resource)->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }
    public function show(Banner $banner)
    {
        $result = $this->BannerRepository->showBanner($banner);
        return $result->ok
            ? ApiResponse::withData(new BannerShowApiResource($result->data))->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerStoreApiRequest $request)
    {
        $result = $this->BannerRepository->createBanner($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('Banner create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(BannerUpdateApiRequest $request, Banner $Banner)
    {
        $result = $this->BannerRepository->updateBanner($request->validated(), $Banner);
        return $result->ok
            ? ApiResponse::withMessage('Banner updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $Banner)
    {
        $result = $this->BannerRepository->deleteBanner($Banner);
        return $result->ok
            ? ApiResponse::withMessage('Banner deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
