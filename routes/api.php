<?php

use App\Http\Controllers\EquipmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('equipment')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [EquipmentController::class, ""]);
    Route::post('/', [EquipmentController::class, ""]);
    Route::get('/{id}', [EquipmentController::class, ""]);
    Route::put('/{id}', [EquipmentController::class, ""]);
    Route::delete('/{id}', [EquipmentController::class, ""]);
});

Route::get('/equipment-type', [EquipmentController::class, ""])->middleware('auth:sanctum');

Route::post('/user/login');
