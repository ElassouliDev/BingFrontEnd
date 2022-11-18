<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMerchantPointsTable extends Migration
{

    public function up()
    {
        Schema::create('user_merchant_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('points')->default(0);
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['user_id', 'branch_id']);
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_merchant_points');
    }
}
