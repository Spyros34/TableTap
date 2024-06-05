<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTableAssociationTable extends Migration
{
    public function up()
    {
        Schema::create('customer_table', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->unique();
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_table');
    }
}
