<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopKitchenTable extends Migration
{
    public function up()
    {
        Schema::create('shop_kitchen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('kitchen_id')->constrained('kitchens')->onDelete('cascade')->unique(); // Ensures each kitchen is only linked to one shop
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_kitchen');
    }
}

