<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKitchenIdUniqueInShopKitchen extends Migration
{
    public function up()
    {
        Schema::table('shop_kitchen', function (Blueprint $table) {
            $table->unique('kitchen_id', 'unique_kitchen_id'); // Ensuring each kitchen is only linked to one shop
        });
    }

    public function down()
    {
        Schema::table('shop_kitchen', function (Blueprint $table) {
            $table->dropUnique('unique_kitchen_id');
        });
    }
}
