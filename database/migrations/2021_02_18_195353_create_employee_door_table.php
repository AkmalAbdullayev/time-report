<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDoorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_door', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("employee_id");
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete("cascade");

            $table->unsignedBigInteger("doors_id");
            $table->foreign('doors_id')
                ->references('id')
                ->on('doors')
                ->onDelete("cascade");

            $table->tinyInteger("employee_device_status")
                ->default(0);

            $table->timestamp("added_at");
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
        Schema::dropIfExists('employee_door');
    }
}
