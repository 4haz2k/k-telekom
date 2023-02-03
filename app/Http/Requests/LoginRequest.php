<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "email" => "required|email",
            "password" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => "Необходимо указать Email.",
            "email.email" => "Несуществующий Email.",
            "password.required" => "Необходимо указать пароль.",
        ];
    }
}
