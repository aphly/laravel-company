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
        Schema::create('company_mail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uuid')->index();
            $table->unsignedBigInteger('level_id')->default(0)->index();
            $table->string('host',128)->nullable();
            $table->string('port',16)->nullable();
            $table->string('encryption',16)->nullable();
            $table->string('username',128)->nullable();
            $table->string('password',255)->nullable();
            $table->string('from_address',255)->nullable();
            $table->string('from_name',255)->nullable();
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
        Schema::dropIfExists('company_mail');
    }
};
