<?php

namespace App\Http\Resources\Admin\ProductResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationsListApiResource extends JsonResource
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
            'value' => $this->value,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'sale_price' => $this->sale_price,
            'date_on_sale_from' => $this->date_on_sale_from,
            'date_on_sale_to' => $this->date_on_sale_to,


        ];
    }
}
