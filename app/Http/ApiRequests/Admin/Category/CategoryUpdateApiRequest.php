<?php

namespace App\Http\ApiRequests\Admin\Category;

use Illuminate\Validation\Rule;
use App\RestfulApi\ApiFormRequest;


class CategoryUpdateApiRequest extends ApiFormRequest
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
            'slug' => 'required|unique:categories,slug',
            'description' => ['required ', Rule::unique('categories', 'slug')->ignore($this->category->id)],
            'parent_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'exists:attributes,id',
            'attribute_is_filter_ids' => 'required',
            'attribute_is_filter_ids.*' => 'exists:attributes,id',
            'variation_id' => 'required|exists:attributes,id',
            'image' => 'required|mimes:jpg,jpeg,png,svg,webp',

        ];
    }
}
