<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoorDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('door_device', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("doors_id");
            $table->foreign("doors_id")
                ->references('id')
                ->on('doors')
                ->onDelete("cascade");
            $table->unsignedBigInteger("device_id");
            $table->foreign("device_id")
                ->references('id')
                ->on('devices')
                ->onDelete("cascade");
            $table->timestamp("added_at");
            $table->tinyInteger("is_come"); // 0 - out, 1 - came
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('door_device');
    }
}
