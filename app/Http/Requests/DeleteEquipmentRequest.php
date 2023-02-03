<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:equipments,id'
        ];
    }

    /**
     * Сообщения валидации
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Необходимо указать ID оборудования.',
            'id.exists' => 'Оборудованния с данным ID не существует.',
        ];
    }
}
