<?php

namespace App\Repository;

use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use App\Models\Brand;

class BrandRepository
{



    public function __construct(private Brand $brand)
    {



    }




    public function createBrand(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {
            return $this->brand->create($inputs);


        });

    }
    public function updateBrand(array $inputs, $brand): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $brand) {

            return $brand->update($inputs);


        });

    }
    public function deleteBrand($brand): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($brand) {

            return $brand->delete();


        });

    }
    public function getAllBrands($search): ServiceResult
    {
        return app(ServiceWrapper::class)(function () use ($search) {
            return $this->brand
                ->where('name', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);

        });

    }


}
