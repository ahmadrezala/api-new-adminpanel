<?php
namespace App\RestfulApi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([
            "type" => "https://tools.ietf.org/html/rfc9110#section-15.5.1",
            "title" => "One or more validation errors occurred.",
            "status" => 400,
            'errors' => $validator->errors()
        ], 400));


    }



    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Access denied'
        ], 403));
    }

}
