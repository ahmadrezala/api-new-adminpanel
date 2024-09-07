<?php

namespace App\Http\ApiRequests\Admin\Auth;

use App\RestfulApi\ApiFormRequest;

class RegisterApiRequest extends ApiFormRequest
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
            'email' => 'required|email| unique:users,email',
            'password' => 'required|min:5|confirmed',

        ];
    }
}
