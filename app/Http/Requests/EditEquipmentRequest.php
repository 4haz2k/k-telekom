<?php

namespace App\Http\Requests;

use App\Http\Services\MaskService;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditEquipmentRequest extends FormRequest
{
    /**
     * Подготовка серийного номера к валидации
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Вытаскиваем ID из url
        $this->merge(['id' => $this->route('id')]);

        $mask = '';

        // Если есть ID типа оборудования, то передаем его маску
        if ($this->has('type_id')) {
            $type = EquipmentType::find($this->get('type_id'));

            if ($type)
                $mask = $type->mask;

        } elseif ( // Если есть ID оборудования и он найден, то достаем маску оборудования, к которому он привязан
            $this->route('id') and $equipment = Equipment::find($this->route('id'))
        ) {
            $mask = $equipment->type->mask;
        }

        // Если маска определилась и серийный номер передан, то проверяем его на соответствие маски оборудования
        if($mask != '' and $this->has('serial_number')) {
            $isSerialValid = MaskService::checkSerial($this->get('serial_number'), $mask);

            $this->merge(['isSerialValid' => $isSerialValid]);
        }
    }

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
            'id' => 'required|exists:equipments,id',
            'type_id' => 'exists:equipment_types,id',
            'serial_number' => [
                Rule::unique('equipments')->ignore($this->id)
            ],
            'isSerialValid' => [
                Rule::in([true])
            ]
        ];
    }
}
