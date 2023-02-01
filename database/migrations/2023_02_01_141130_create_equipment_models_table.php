<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('type_id')
                ->comment('ID типа оборудования');

            $table->unsignedBigInteger('serial_number')
                ->unique()
                ->comment('Серийный номер');

            $table->longText('description')
                ->nullable()
                ->comment('Примечание');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type_id')->references('id')->on('equipment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_models');
    }
}
