<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EquipmentController extends Controller
{
    /**
     * Вывод пагинированного списка оборудования с возможностью
     * поиска путем указания serial и description параметров
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getList(Request $request): AnonymousResourceCollection
    {
        $equipment = Equipment::query();

        if($request->has('serial'))
            $equipment = $equipment->where('serial_number', 'like', "%{$request->get('serial')}%");

        if($request->has('description'))
            $equipment = $equipment->where('description', 'like', "%{$request->get('description')}%");

        $equipment = $equipment->paginate(10);

        return EquipmentResource::collection($equipment);
    }

    /**
     * Запрос данных по id оборудования
     *
     * @param numeric $id
     * @return EquipmentResource
     */
    public function getEquipment($id): EquipmentResource
    {
        return EquipmentResource::make(Equipment::findOrFail($id));
    }
}
