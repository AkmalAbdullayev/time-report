<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComeOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('come_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doors_has_device_id');
            $table->foreign('doors_has_device_id')
                ->references('id')
                ->on('door_device')
                ->onDelete('cascade');

            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->timestamp("action_time");
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
        Schema::dropIfExists('come_outs');
    }
}
