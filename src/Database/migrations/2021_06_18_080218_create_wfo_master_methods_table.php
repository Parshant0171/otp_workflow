<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateWfoMasterMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfo_master_methods', function (Blueprint $table) {
            $table->id();

            $table->string('method');

            $table->auditableWithDeletes();

            $table->timestampTz('created_at', $precision = 0)->useCurrent();
            $table->timestampTz('updated_at', $precision = 0)->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });

        DB::table('wfo_master_methods')->insert(array('method' => 'OTP on Approval'));

        DB::table('wfo_master_methods')->insert(array('method' => 'Public Link Approval'));

        DB::table('wfo_master_methods')->insert(array('method' => 'Preemptive OTP'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfo_master_methods');
    }
}
