<?php

namespace App\Http\ApiRequests\Admin\Brand;

use Illuminate\Validation\Rule;
use App\RestfulApi\ApiFormRequest;

class BrandUpadateApiRequest extends ApiFormRequest
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
            'slug' => ['required ', Rule::unique('brands', 'slug')->ignore($this->brand->id)],
            'is_active' => 'required'
        ];
    }
}
