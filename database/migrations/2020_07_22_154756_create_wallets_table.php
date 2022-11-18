<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('t_type', [\App\Models\Wallet::ADMIN_CHARGING, \App\Models\Wallet::CANCEL_ORDER, \App\Models\Wallet::NEW_ORDER]);//->comment('1 admin charging | 2 cancel order');
            $table->float('amount')->default(0);
            $table->string('note')->nullable();
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);



            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
