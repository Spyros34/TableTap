<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * This method defines the table structure.
     */
    public function up()
    {
        Schema::create('system_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade'); // Ensures FK linked to Systems
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade')->unique(); // Ensures FK linked to Admins and each admin controls only one system
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method is called when the migration is rolled back.
     */
    public function down()
    {
        Schema::dropIfExists('system_admin');
    }
}
