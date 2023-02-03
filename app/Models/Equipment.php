<?php

namespace App\Models;

use App\Http\Requests\EditEquipmentRequest;
use App\Http\Requests\StoreEquipmentRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipments';

    /**
     * Получение типа оборудования
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'type_id');
    }

    /**
     * Редактирование записи
     *
     * @param EditEquipmentRequest $request
     * @return bool
     */
    public static function edit(EditEquipmentRequest $request): bool
    {
        $equipment = static::find($request->route('id'));

        if($request->has('type_id'))
            $equipment->type_id = $request->get('type_id');

        if($request->has('serial_number'))
            $equipment->serial_number = $request->get('serial_number');

        if($request->has('description'))
            $equipment->description = $request->get('description');

        return $equipment->save();
    }

    public static function saveEquipment(StoreEquipmentRequest $request): bool
    {
        $data = [];
        foreach ($request->get('serial_numbers') as $serial_number) {
            $data[] = [
                'type_id' => $request->get('type_id'),
                'serial_number' => $serial_number['number'],
                'description' => $request->get('description'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        }

        return static::insert($data);
    }
}
