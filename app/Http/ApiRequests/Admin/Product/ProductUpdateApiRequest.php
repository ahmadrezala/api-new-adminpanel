<?php

namespace App\Http\ApiRequests\Admin\Product;

use Illuminate\Validation\Rule;
use App\RestfulApi\ApiFormRequest;

class ProductUpdateApiRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required',
            'slug' => ['required ', Rule::unique('products', 'slug')->ignore($this->product->id)],
            'tag_ids' => 'required',
            'tag_ids.*' => 'exists:tags,id',
            'description' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.price.*' => 'required|integer',
            'variation_values.quantity.*' => 'required|integer',
            'variation_values.sale_price' => 'nullable|integer',
            'variation_values.date_on_sale_from' => 'nullable|date',
            'variation_values.date_on_sale_to' => 'nullable|date',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',

        ];
    }
}
