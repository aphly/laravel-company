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
        Schema::create('company_mail_task_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mail_task_id')->index();
            $table->unsignedBigInteger('order_id')->index();
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
        Schema::dropIfExists('company_mail_task_order');
    }
};
