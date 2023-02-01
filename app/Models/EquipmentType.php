<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{
    use HasFactory;

    protected $table = 'equipment_types';

    /**
     * Получение оборудований, привязанных к этому типу
     *
     * @return HasMany
     */
    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, "type_id");
    }
}
