<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOrderIdForeignOnOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']); // 刪除原來的外鍵
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // 創建新的外鍵
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']); // 刪除新的外鍵
            $table->foreign('order_id')->references('id')->on('orders'); // 恢復原來的外鍵
        });
    }
}
