<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable("schedules")) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->unsignedBigInteger("type")->nullable();
                $table->string("range_from");
                $table->string("range_to");
                $table->text("description")->nullable();
                $table->timestamps();
            });
        }

        Schema::disableForeignKeyConstraints();

        Schema::table("employees", function (Blueprint $table) {
            $table->foreignId("schedule_id")->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
