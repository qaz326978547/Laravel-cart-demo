<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckoutedToCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('carts', 'checkouted')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->boolean('checkouted')->default(0); //新增checkouted字段 默認值為false
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'checkouted')) {
                $table->dropColumn('checkouted'); //删除checkouted字段
            }
        });
    }
}
