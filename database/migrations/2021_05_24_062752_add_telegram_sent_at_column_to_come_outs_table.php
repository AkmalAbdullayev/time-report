<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelegramSentAtColumnToComeOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('come_outs', function (Blueprint $table) {
            $table->timestamp('telegram_sent_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('come_outs', function (Blueprint $table) {
            $table->dropColumn('telegram_sent_at');
        });
    }
}
