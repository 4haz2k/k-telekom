<?php

namespace Database\Factories;

use App\Http\Services\MaskService;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $equipmentType = EquipmentType::inRandomOrder()->first();

        return [
            'type_id' => $equipmentType->id,
            'serial_number' => $this
                ->faker
                ->regexify($this->generateSerialByMask($equipmentType->mask)),
        ];
    }

    /**
     * Сгенерировать серийный номер по маске
     *
     * @param string $mask
     * @return string
     */
    private function generateSerialByMask(string $mask): string
    {
        $pattern = [
            'N' => '[0-9]',
            'A' => '[A-Z]',
            'a' => '[a-z]',
            'X' => '[A-Z0-9]',
            'Z' => '[\-_\@]'
        ];

        $maskChars = str_split($mask);

        $result = '';

        foreach ($maskChars as $maskChar)
            $result .= $pattern[$maskChar];

        return '/^' . $result . '/';
    }
}
