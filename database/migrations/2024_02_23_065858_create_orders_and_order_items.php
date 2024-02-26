<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersAndOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('cart_id');
            $table->foreign('user_id')->references('id')->on('users');
            //建立cart_id的外建 關連到 carts table的id
            $table->foreign('cart_id')->references('id')->on('carts');
            //新增status字段 默認值為unpaid 未支付 paid 已支付 completed 已完成 canceled 取消
            $table->enum('status',['unpaid','paid','completed','canceled'])->default('unpaid');
            $table->timestamps();
        });

        Schema::create('order_items',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
}
