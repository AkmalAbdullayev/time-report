<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFingerStatusDoorDeviceColumnsToEmployeeDoorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_door', function (Blueprint $table) {
            $table->unsignedBigInteger('door_device_id')->nullable()->after('doors_id');
            $table->foreign('door_device_id')->references('id')->on('door_device');
            $table->tinyInteger('employee_finger_status')->nullable()->after('employee_device_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_door', function (Blueprint $table) {
            $table->dropColumn('door_device_id');
            $table->dropColumn('employee_finger_status');
        });
    }
}
