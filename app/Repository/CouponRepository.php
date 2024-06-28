<?php

namespace App\Repository;

use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use App\Models\Coupon;

class CouponRepository
{

    public function __construct(private Coupon $coupon)
    {

    }


    public function createCoupon(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {

            $this->coupon->create($inputs);

        });


    }
    public function getAllCoupons(): ServiceResult
    {

        return app(ServiceWrapper::class)(function () {

            return $this->coupon->latest()->paginate(4);

        });


    }


}
