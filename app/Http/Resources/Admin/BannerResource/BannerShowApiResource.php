<?php

namespace App\Http\Resources\Admin\BannerResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerShowApiResource extends JsonResource
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
            'title' => $this->title,
            'image' => url($this->image),
            'text' => $this->text,
            'priority' => $this->priority,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,

        ];
    }
}
