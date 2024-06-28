<?php

namespace App\Repository;

use App\Base\ServiceResult;
use App\Models\UserAddress;
use App\Base\ServiceWrapper;

class UserAddressRepository
{
    public function __construct(private UserAddress $UserAddress)
    {



    }




    public function createUserAddress(array $inputs): ServiceResult
    {
        // dd($inputs);

        return app(ServiceWrapper::class)(function () use ($inputs) {
            // auth()->id(),
            return $this->UserAddress->create([
                'user_id' => '1',
                'title' => $inputs['title'],
                'cellphone' => $inputs['cellphone'],
                'province_id' => $inputs['province_id'],
                'city_id' => $inputs['city_id'],
                'address' => $inputs['address'],
                'postal_code' => $inputs['postal_code']
            ]);


        });

    }
    public function updateUserAddress(array $inputs, $userAddress): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $userAddress) {

            return $userAddress->update($inputs);


        });

    }
    public function deleteUserAddress($userAddress): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($userAddress) {

            return $userAddress->delete();


        });

    }
    public function getAllUserAddresss(): ServiceResult
    {
        return app(ServiceWrapper::class)(function () {
            return $this->UserAddress->orderBy('id', 'desc')->paginate();


        });

    }


}
