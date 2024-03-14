<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesUsesSmsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfo_services_uses_sms_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wfo_service_id');          
            $table->foreign('wfo_service_id')->references('id')->on('wfo_services')->onDelete('cascade');

            $table->foreignId('master_sms_service_id');          
            $table->foreign('master_sms_service_id')->references('id')->on('wfo_master_sms_services')->onDelete('cascade');

            $table->auditableWithDeletes();

            $table->timestampTz('created_at', $precision = 0)->useCurrent();
            $table->timestampTz('updated_at', $precision = 0)->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wfo_services_uses_sms_services');
    }
}
