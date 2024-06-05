<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKitchenForeignKeyToShopKitchenTable extends Migration
{
    public function up()
    {
        Schema::table('shop_kitchen', function (Blueprint $table) {
            $table->foreign('kitchen_id')->references('id')->on('kitchens')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('shop_kitchen', function (Blueprint $table) {
            $table->dropForeign(['kitchen_id']);
        });
    }
}
