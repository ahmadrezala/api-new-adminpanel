<?php

namespace App\Repository;

use App\Models\Attribute;
use App\Base\ServiceResult;
use App\Base\ServiceWrapper;

class AttributeRepository
{

    public function __construct(private Attribute $Attribute)
    {



    }




    public function createAttribute(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {
            return $this->Attribute->create($inputs);


        });

    }
    public function updateAttribute(array $inputs, Attribute $attribute): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $attribute) {

            return $attribute->update($inputs);


        });

    }
    public function deleteAttribute(Attribute $attribute): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($attribute) {

            return $attribute->delete();


        });

    }
    public function getAllAttributes(): ServiceResult
    {
        return app(ServiceWrapper::class)(function () {
            return $this->Attribute->orderBy('id', 'desc')->paginate(2);


        });

    }


}
