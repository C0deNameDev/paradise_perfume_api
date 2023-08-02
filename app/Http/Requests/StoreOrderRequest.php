<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'perfume_id' => 'required|integer',
            'bottles' => 'required|array',
            'bottles.*' => 'integer',
            'quantities' => 'required|array',
            'quantities.*' => 'integer',
            'total_price' => 'required|numeric',
            'client_id' => 'required|integer',
        ];
    }
}
