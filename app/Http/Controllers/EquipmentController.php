<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditEquipmentRequest;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\EquipmentTypeResource;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Http\JsonResponse;
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

        if($request->has('query'))
            $equipment = $equipment
                ->where('serial_number', 'like', "%{$request->get('query')}%")
                ->orWhere('description', 'like', "%{$request->get('query')}%");

        $equipment = $equipment->orderByDesc('created_at')->paginate(10);

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

    /**
     * Вывод пагинированного списка типов оборудования с возможностью
     * поиска путем указания name и mask параметров
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getTypesList(Request $request): AnonymousResourceCollection
    {
        $equipmentTypes = EquipmentType::query();

        if($request->has('name'))
            $equipmentTypes = $equipmentTypes->where('name', 'like', "%{$request->get('name')}%");

        if ($request->has('mask'))
            $equipmentTypes = $equipmentTypes->where('mask', 'like', "%{$request->get('mask')}%");

        $equipmentTypes = $equipmentTypes->paginate(10);

        return EquipmentTypeResource::collection($equipmentTypes);
    }

    /**
     * Удаление записи Equipments (мягкое удаление)
     *
     * @param numeric $id
     * @return JsonResponse
     */
    public function deleteEquipment($id): JsonResponse
    {
        $equipment = Equipment::findOrFail($id);

        if ($equipment->delete())
            return response()->json(['message' => 'Delete successful']);
        else
            return response()->json(['message' => 'Failed to delete']);
    }

    /**
     * Редактирование записи Equipments
     *
     * @param EditEquipmentRequest $request
     * @return JsonResponse
     */
    public function editEquipment(EditEquipmentRequest $request): JsonResponse
    {
        if (Equipment::edit($request))
            return response()->json(['message' => 'Save successful']);
        else
            return response()->json(['message' => 'Failed to save']);
    }

    /**
     * Сохранение новых записей Equipments
     *
     * @param StoreEquipmentRequest $request
     * @return JsonResponse
     */
    public function addEquipment(StoreEquipmentRequest $request): JsonResponse
    {
        if(Equipment::saveEquipment($request))
            return response()->json(['message' => 'Save successful']);
        else
            return response()->json(['message' => 'Failed to save']);
    }
}
