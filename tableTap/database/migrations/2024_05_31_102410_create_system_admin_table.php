<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAdminTable extends Migration
{
    public function up()
    {
        Schema::create('system_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_admin');
    }
}
