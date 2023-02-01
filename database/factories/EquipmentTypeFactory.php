<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentTypeFactory extends Factory
{
    /**
     * Генерация
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(20),
            'mask' => implode('', $this->faker->randomElements(['N', 'A', 'a', 'X', 'Z'], 10, true))
        ];
    }
}
