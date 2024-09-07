<?php

namespace App\Http\Resources\Admin\ProductResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\ProductResource\AttributeListApiResource;

class AttributescategoryListApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'attributes' => AttributeListApiResource::collection($this->attributes->where('pivot.is_variation', 0)),
            'attributes_is_variation' => $this->attributes->where('pivot.is_variation', 1)->first()?->name,


        ];
    }
}
