<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('equipment')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [EquipmentController::class, "getList"])->name('equipment_list');
    Route::post('/', [EquipmentController::class, ""])->name('equipment_insert');
    Route::get('/{id}', [EquipmentController::class, "getEquipment"])->name('equipment_get');
    Route::put('/{id}', [EquipmentController::class, "editEquipment"])->name('equipment_edit');
    Route::delete('/{id}', [EquipmentController::class, "deleteEquipment"])->name('equipment_delete');
});

Route::get('/equipment-type', [EquipmentController::class, "getTypesList"])->middleware('auth:sanctum');

Route::post('/user/login', [AuthController::class, "loginUser"]);
