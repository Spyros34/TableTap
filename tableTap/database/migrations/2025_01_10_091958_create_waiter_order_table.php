<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaiterOrderTable extends Migration
{
    public function up()
    {
        Schema::create('waiter_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waiter_id')
                  ->constrained('waiters')
                  ->onDelete('cascade');
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waiter_order');
    }
}