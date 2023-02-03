<?php

namespace App\Http\Requests;

use App\Http\Services\MaskService;
use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreEquipmentRequest extends FormRequest
{
    /**
     * Серийные номера
     *
     * @var array
     */
    private array $serials = [];

    /**
     * Подготовка серийного номера к валидации
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $mask = '';

        // Если есть ID типа оборудования, то передаем его маску
        if ($this->has('type_id')) {
            $type = EquipmentType::find($this->get('type_id'));

            if ($type)
                $mask = $type->mask;
        }

        // Если маска определилась и серийный номера переданы, то проверяем их на соответствие маски оборудования
        if($mask != '' and $this->has('serial_numbers')) {
            $serialNumbers = $this->get('serial_numbers');

            $isSerialValid = true;

            // Проверка на соответствие маске
            foreach ($serialNumbers as $serialNumber) {
                if(empty($serialNumber)) {
                    $isSerialValid = false;
                    continue;
                }

                $this->serials[] = $serialNumber['number'];

                if(!MaskService::checkSerial($serialNumber['number'], $mask))
                    $isSerialValid = false;
            }

            $this->merge(['isSerialValid' => $isSerialValid]);

            // Проверка на исключение повторяющихся серийных номеров
            $serialNumberEquals = array_count_values($this->serials);
            $isSerialsAreUnique = true;

            foreach ($serialNumbers as $serialNumber) {
                if(empty($serialNumber)) {
                    continue;
                }

                if ($serialNumberEquals[$serialNumber['number']] !== 1) {
                    $isSerialsAreUnique = false;
                }
            }

            $this->merge(['isSerialsAreUnique' => $isSerialsAreUnique]);
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
            'type_id' => 'exists:equipment_types,id',
            'serial_numbers.*.number' => [
                Rule::unique('equipments', 'serial_number')
            ],
            'isSerialValid' => [
                Rule::in([true])
            ],
            'isSerialsAreUnique' => [
                Rule::in([true])
            ]
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
            'type_id.exists' => 'Данного типа оборудования не существует.',
            'serial_numbers.*.number.unique' => 'Некоторые серийные номера не уникальные.',
            'isSerialValid.in' => 'Некоторые серийные номера не соответствуют маске оборудования.',
            'isSerialsAreUnique.in' => 'Серийные номера повторяются.'
        ];
    }
}
