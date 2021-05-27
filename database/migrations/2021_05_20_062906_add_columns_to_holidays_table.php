<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->tinyInteger('type')->default(1)->after('id');
            $table->string('name', 45)->after('type');
            $table->date('start_date')->after('name');
            $table->tinyInteger('number_of_days')->after('start_date');
            $table->tinyInteger('calculate_as_overtime')->after('number_of_days');
            $table->tinyInteger('repeat_annually')->after('calculate_as_overtime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            //
        });
    }
}
