<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWfoOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wfo_otps', function (Blueprint $table) {
            $table->id();

            $table->string('model');
            $table->integer('model_id');
            $table->string('otp')->nullable(true);
            $table->string('public_link')->nullable(true);
            $table->datetime('expires_at');
            $table->smallInteger('is_verified');
            $table->datetime('verification_date_time')->nullable(true);
            $table->json('additional_properties')->nullable(true);

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
        Schema::dropIfExists('wfo_otps');
    }
}
