<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => 'required|string|exists:users,mobile',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.exists' => __('No user found with this mobile number.'),
        ];
    }
}
