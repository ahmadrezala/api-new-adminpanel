<?php

namespace App\Http\Resources\Admin\ProductResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListApiResource extends JsonResource
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
            'primary_image' => url($this->primary_image),
            'category' => $this->category->name,
            'brand' => $this->brand->name,

        ];
    }
}
