<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTkToIntegerInShopsTable extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->integer('tk')->change();
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->smallInteger('tk')->change();
        });
    }
}
