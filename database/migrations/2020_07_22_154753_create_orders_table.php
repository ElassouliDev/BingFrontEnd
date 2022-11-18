<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->integer('status')->default(\App\Models\Order::status['WORKING']);
            $table->float('total_cost');//Amount to be paid (meals_cost + delivery_cost + tax_cost + commission_cost - coupon_discount)
            $table->float('meals_cost');//The cost of the products
            $table->text('note')->nullable();
            $table->string('attach_invoice')->nullable();
            $table->boolean('isRated')->default(0);
            $table->dateTime('pick_up_time');
            $table->boolean('all_order_object_filled_out')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('ordered')->default(1);
            $table->boolean('draft')->default(0);


            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
            $table->foreign('employee_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
