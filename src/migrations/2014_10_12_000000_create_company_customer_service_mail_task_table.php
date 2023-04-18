<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_customer_service_mail_task', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uuid')->index();
            $table->unsignedBigInteger('level_id')->default(0)->index();
            $table->unsignedBigInteger('mail_template_id')->index();
            $table->unsignedBigInteger('mail_id')->index();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->unsignedBigInteger('created_at');
            $table->unsignedBigInteger('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_customer_service_mail_task');
    }
};
