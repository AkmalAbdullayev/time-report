<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualComeOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_come_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("employees_id")->constrained("employees");
            $table->foreignId("users_id")->constrained("users");
            $table->dateTime("come_at");
            $table->dateTime("left_at");
            $table->tinyInteger("reason")->default(0); // 0 - without reason, 1 - with reason
            $table->text("description")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('manual_come_outs');
    }
}
