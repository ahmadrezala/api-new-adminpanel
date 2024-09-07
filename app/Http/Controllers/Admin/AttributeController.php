<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\AttributeRepository;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Attribute\AttributeStoreApiRequest;
use App\Http\ApiRequests\Admin\Attribute\AttributeUpdateApiRequest;
use App\Http\Resources\Admin\AttributeResource\AttributeListApiResource;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(private AttributeRepository $attributeRepository)
    {
    }




    public function index(Request $request)
    {
        $result = $this->attributeRepository->getAllAttributes($request->query('search'));
        return $result->ok
            ? ApiResponse::withData([
                'attributes' => AttributeListApiResource::collection($result->data)->response()->getData()->data,
                'total_pages' => AttributeListApiResource::collection($result->data)->response()->getData()->meta->last_page,
                'current_page' => AttributeListApiResource::collection($result->data)->response()->getData()->meta->current_page,

            ])->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeStoreApiRequest $request)
    {

        $result = $this->attributeRepository->createAttribute($request->validated());
        return $result->ok
            ? ApiResponse::withData(new AttributeListApiResource($result->data))->withMessage('Attribute create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    public function show(Attribute $attribute)
    {

        return ApiResponse::withData($attribute)->build()->response();

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeUpdateApiRequest $request, Attribute $Attribute)
    {
        $result = $this->attributeRepository->updateAttribute($request->validated(), $Attribute);
        return $result->ok
            ? ApiResponse::withData($result->data)->withMessage('Attribute updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $Attribute)
    {
        $result = $this->attributeRepository->deleteAttribute($Attribute);
        return $result->ok
            ? ApiResponse::withMessage('Attribute deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
