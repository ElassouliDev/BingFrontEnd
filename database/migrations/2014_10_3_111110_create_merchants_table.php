<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Merchant;

class CreateMerchantsTable extends Migration
{
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_type_id')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('name')->nullable();
            $table->string('email');//->unique();
            $table->string('phone');//->unique();
            $table->string('whatsapp')->nullable();
            $table->integer('status')->default(Merchant::NOT_ACTIVE);

            $table->integer('gender')->default(MALE);
            $table->string('generatedCode')->nullable();

            $table->float('lat', 8, 5)->nullable();
            $table->float('lng', 8, 5)->nullable();

            $table->enum('local', ['en','ar'])->default('ar');

            $table->boolean('busy')->default(false);



            $table->integer('max_orders')->default(10);
            $table->float('rate', 2, 1)->default(0);
            $table->integer('min_price')->default(0);

            $table->string('image')->nullable();
            $table->string('cover')->nullable();

            $table->string('i_ban')->nullable();
            $table->string('id_no')->nullable();
            $table->string('id_file')->nullable();
            $table->string('swift_code')->nullable();

            $table->string('comm_registration_no')->nullable();
            $table->string('comm_registration_file')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();


            $table->string('password');

            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);

            $table->boolean('accepted')->default(0);


            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['email', 'deleted_at']);
            $table->unique(['phone', 'deleted_at']);


            $table->foreign('merchant_type_id')->references('id')->on('merchant_types')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->cascadeOnDelete();
        });
    }


    public function down()
    {
        Schema::dropIfExists('merchants');
    }
}

