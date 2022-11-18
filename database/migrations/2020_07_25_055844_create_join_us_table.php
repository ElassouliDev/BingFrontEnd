<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoinUsTable extends Migration
{

    public function up()
    {
        Schema::create('join_us', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('i_band')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('id_no')->nullable();
            $table->string('i_ban')->nullable();
            $table->string('id_file')->nullable();
            $table->string('comm_registration_file')->nullable();
            $table->string('comm_registration_no')->nullable();
            $table->integer('gender')->default(MALE);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
//            $table->string('merchant_name');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('merchant_type_id');
            $table->timestamps();
            $table->softDeletes();


            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);




            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreign('bank_id')->references('id')->on('banks')->cascadeOnDelete();
            $table->foreign('merchant_type_id')->references('id')->on('merchant_types')->cascadeOnDelete();
        });
    }


    public function down()
    {
        Schema::dropIfExists('join_us');
    }
}
