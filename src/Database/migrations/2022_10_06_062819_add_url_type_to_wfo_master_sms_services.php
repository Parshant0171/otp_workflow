<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlTypeToWfoMasterSmsServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wfo_master_sms_services', function (Blueprint $table) {
            $table->string('url_type')->nullable();
            $table->boolean('in_use')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfo_master_sms_services', function (Blueprint $table) {
            $table->dropColumn(['url_type']);
            $table->dropColumn(['in_use']);
        });
    }
}
