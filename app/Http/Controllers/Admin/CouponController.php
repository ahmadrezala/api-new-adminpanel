<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repository\CouponRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Coupon\CouponStoreApiRequest;
use App\Http\Resources\Admin\CouponResource\CouponListApiResource;

class CouponController extends Controller
{


    public function __construct(private CouponRepository $couponRepository)
    {
    }


    public function index()
    {
        $result = $this->couponRepository->getAllCoupons();
        return $result->ok
            ? ApiResponse::withData(CouponListApiResource::collection($result->data)->resource)->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }
    public function store(CouponStoreApiRequest $request)
    {

        $result = $this->couponRepository->createCoupon($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('Coupon create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }
}
