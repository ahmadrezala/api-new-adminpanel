<?php

namespace App\Http\Resources\Admin\CategoryResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryApiResource extends JsonResource
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
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'image' => url($this->image),
            'attributes' => AttributeListApiResource::collection($this->attributes),
            'attributes_is_filter' => AttributeListApiResource::collection(
                $this->attributes->where('pivot.is_filter', 1)
            ),
            'attributes_is_variation' => $this->attributes->where('pivot.is_variation', 1)->first()?->id,

        ];
    }
}
