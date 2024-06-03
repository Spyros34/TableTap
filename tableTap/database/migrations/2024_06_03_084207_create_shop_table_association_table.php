<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopTableAssociationTable extends Migration
{
    public function up()
    {
        Schema::create('shop_table_association', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');  // Reference to shops
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade'); // Reference to tables
            $table->unique('table_id');  // Ensure each table is associated with only one shop
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_table_association');
    }
}