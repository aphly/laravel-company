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
        Schema::create('company_order_mail', function (Blueprint $table) {
            $table->id();
            $table->string('order_id',64)->index();
            $table->unsignedBigInteger('mail_template_id')->index();
            $table->unsignedBigInteger('mail_id')->index();
            $table->tinyInteger('status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_order_mail');
    }
};
