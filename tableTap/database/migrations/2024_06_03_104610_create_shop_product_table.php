<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductTable extends Migration
{
    public function up()
    {
        Schema::create('shop_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->unique(); // Ensure each product is sold in only one shop
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_product');
    }
}

