<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKitchenIdUniqueInShopKitchen extends Migration
{
    public function up()
    {
        Schema::table('shop_kitchen', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            $table->dropForeign(['kitchen_id']);
            // Ensure kitchen_id is unique
            $table->unique('kitchen_id', 'unique_kitchen_id');
        });
    }

    public function down()
    {
        Schema::table('shop_kitchen', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('unique_kitchen_id');
            // Add the foreign key constraint back
            $table->foreign('kitchen_id')->references('id')->on('kitchens')->onDelete('cascade');
        });
    }
}

