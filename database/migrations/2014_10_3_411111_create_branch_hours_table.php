<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchHoursTable extends Migration
{
    public function up()
    {
        Schema::create('branch_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->enum('day', [1,2,3,4,5,6,7]);
            $table->time('from');
            $table->time('to');
            $table->timestamps();
            $table->softDeletes();

            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('branch_hours');
    }
}
