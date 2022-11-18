<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchPointsPrivacyTable extends Migration
{
    public function up()
    {
        Schema::create('branch_points_privacy', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->integer('new_order')->default(0);
            $table->integer('when_merchant_late')->default(0);
            $table->integer('rate_order')->default(0);
            $table->integer('ready_01')->default(0);
            $table->integer('ready_04')->default(0);
            $table->integer('ready_plus_04')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);
            $table->unique(['branch_id', 'new_order']);
            $table->unique(['branch_id', 'when_merchant_late']);
            $table->unique(['branch_id', 'rate_order']);
            $table->unique(['branch_id', 'ready_01']);
            $table->unique(['branch_id', 'ready_04']);
            $table->unique(['branch_id', 'ready_plus_04']);
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_points_privacy');
    }
}
