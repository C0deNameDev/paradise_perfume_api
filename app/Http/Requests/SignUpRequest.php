<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email",
            "firstname" => "required|string|max:50",
            "lastname" => "required|string|max:50",
            "phoneNumber" => "required|string|max:10|min:10",
            "image" => "string|nullable",
            "password" => "required|string|min:8|max:100",
            "confirmPassword" => "required|string|same:password"
        ];
    }
}