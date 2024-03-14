<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntityTemplateIdInWfoServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wfo_services', function (Blueprint $table) {
            $table->string('entity_id')->before('message_text');
            $table->string('template_id')->before('message_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wfo_services', function (Blueprint $table) {
            $table->dropColumn(['entity_id', 'template_id']);
        });
    }
}
