<?php

namespace App\Http\Controllers\Home;

use App\Models\UserAddress;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Repository\UserAddressRepository;
use App\Http\ApiRequests\Home\Address\UserAddressApiRequest;
use App\Http\Resources\Home\UserAddressResource\UserAddressListApiResource;

class UserAddressController extends Controller
{
    public function __construct(private UserAddressRepository $UserAddressRepository)
    {
    }




    public function index()
    {
        $result = $this->UserAddressRepository->getAllUserAddresss();
        return $result->ok
            ? ApiResponse::withData(UserAddressListApiResource::collection($result->data)->resource)->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAddressApiRequest $request)
    {
        $result = $this->UserAddressRepository->createUserAddress($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('UserAddress create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserAddressApiRequest $request, UserAddress $UserAddress)
    {
        $result = $this->UserAddressRepository->updateUserAddress($request->validated(), $UserAddress);
        return $result->ok
            ? ApiResponse::withMessage('UserAddress updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddress $UserAddress)
    {
        $result = $this->UserAddressRepository->deleteUserAddress($UserAddress);
        return $result->ok
            ? ApiResponse::withMessage('UserAddress deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
