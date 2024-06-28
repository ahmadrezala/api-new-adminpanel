<?php

namespace App\Http\Resources\Admin\CategoryResource;

use App\Http\Resources\Admin\AttributeResource\AttributeListApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryListApiResource extends JsonResource
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
            'description' => $this->description,
            'image' => url($this->image),
            'attributes' => AttributeListApiResource::collection($this->attributes)


        ];



    }
}
