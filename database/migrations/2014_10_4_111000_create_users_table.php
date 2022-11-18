<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();//->unique();
            $table->string('phone')->unique();
            $table->integer('type')->default(\App\Models\User::type['CUSTOMER']);
            $table->boolean('notification')->default(false);
            $table->integer('gender')->default(MALE);
            $table->boolean('verified')->default(false);
            $table->boolean('hide_mobile')->default(false);
            $table->boolean('hide_email')->default(false);
            $table->float('rate', 2, 1)->default(0);
            $table->string('generatedCode')->nullable();
            $table->float('lat', 8, 5)->nullable();
            $table->float('lng', 8, 5)->nullable();

            $table->string('image')->nullable();
            $table->enum('local', ['en', 'ar'])->default('ar');
            $table->string('password')->nullable();
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['phone', 'deleted_at']);
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
