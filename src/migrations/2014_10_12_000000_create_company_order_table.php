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
        Schema::create('company_order', function (Blueprint $table) {
            $table->string('order_id',64)->primary();
            $table->string('email',128);
            $table->string('firstname',64);
            $table->string('lastname',64);
            $table->string('country',64);
            $table->string('zone',64);
            $table->string('city',64);
            $table->string('address',128);
            $table->string('postcode',16);
            $table->string('telephone',32);
            $table->decimal('price');
            $table->string('currency',16);
            $table->dateTime('add_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_order');
    }
};
