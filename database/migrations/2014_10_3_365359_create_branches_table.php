<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{

    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->boolean('isMainBranch')->default(false);
            $table->boolean('isOpen')->default(true);
            $table->integer('total_rates_number')->default(0);
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('cover')->nullable();
            $table->string('email');//->unique();
            $table->string('phone');//->unique();
            $table->integer('status')->default(\App\Models\Merchant::NOT_ACTIVE);
            $table->integer('gender')->default(MALE);
            $table->string('generatedCode')->nullable();
            $table->float('lat', 8, 5)->nullable();
            $table->float('lng', 8, 5)->nullable();
            $table->enum('local', ['en', 'ar'])->default('ar');
            $table->float('rate', 2, 1)->default(0);
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('password');
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);
            $table->boolean('accepted')->default(0);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
            $table->unique(['email', 'deleted_at']);
            $table->unique(['phone', 'deleted_at']);
//            $table->unique(['name', 'merchant_id']);

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
