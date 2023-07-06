<?php

namespace App\Http\Requests;

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
            'email' => 'required|email',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:10|min:10',
            'image' => 'string|nullable',
            'password' => 'required|string|min:8|max:100',
            'confirm_password' => 'required|string|same:password',
        ];
    }
}
