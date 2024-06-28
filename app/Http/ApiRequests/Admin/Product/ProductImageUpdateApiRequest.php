<?php

namespace App\Http\ApiRequests\Admin\Product;

use App\RestfulApi\ApiFormRequest;

class ProductImageUpdateApiRequest extends ApiFormRequest
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
            'primary_image' => 'required_if:products,primary_image,null|mimes:jpg,jpeg,png,svg,webp',
            'images' => 'required_if:product_images,image,null',
            'images.*' => 'mimes:jpg,jpeg,png,svg,webp',

        ];
    }
}
