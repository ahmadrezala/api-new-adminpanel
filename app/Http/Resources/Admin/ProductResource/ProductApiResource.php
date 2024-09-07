<?php

namespace App\Http\Resources\Admin\ProductResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\ProductResource\BrandListApiResource;
use App\Http\Resources\Admin\ProductResource\ProductAttributesListApiResource;

class ProductApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'category' => $this->category->name,
            'description' => $this->description,
            'delivery_amount' => $this->delivery_amount,
            'delivery_amount_per_product' => $this->delivery_amount_per_product,
            'tags' => TagListApiResource::collection($this->tags),
            'attributes' =>ProductAttributesListApiResource::collection($this->attributes),
            'variations' =>ProductVariationsListApiResource::collection($this->variations),
            'brand' => $this->brand->id,

        ];
    }
}
