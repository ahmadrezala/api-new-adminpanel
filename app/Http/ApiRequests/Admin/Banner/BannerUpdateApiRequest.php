<?php

namespace App\Http\ApiRequests\Admin\Banner;

use App\RestfulApi\ApiFormRequest;

class BannerUpdateApiRequest extends ApiFormRequest
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
            'title' => 'required',
            'text' => 'required',
            'is_active' => 'required',
            'button_text' => 'required',
            'button_link' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,svg,webp',
            'priority' => 'required|integer',
            'type' => 'required'
        ];
    }
}
