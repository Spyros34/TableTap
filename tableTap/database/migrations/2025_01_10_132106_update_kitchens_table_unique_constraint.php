<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKitchensTableUniqueConstraint extends Migration
{
    public function up()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->dropUnique(['name']); // Remove the global unique constraint
            $table->unique(['name', 'shop_id']); // Add a composite unique constraint
        });
    }

    public function down()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            $table->dropUnique(['name', 'shop_id']); // Remove the composite unique constraint
            $table->unique('name'); // Re-add the global unique constraint
        });
    }
}