<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKitchenNameUnique extends Migration
{
    public function up()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            // Adding a unique constraint to the 'name' column
            $table->string('name')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            // Removing the unique constraint from the 'name' column
            $table->dropUnique(['name']);
        });
    }
}
