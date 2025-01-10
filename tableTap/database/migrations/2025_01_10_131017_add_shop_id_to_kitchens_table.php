<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopIdToKitchensTable extends Migration
{
    public function up()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            if (!Schema::hasColumn('kitchens', 'shop_id')) {
                $table->unsignedBigInteger('shop_id')->after('id');
                $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            }
        });
    }
    
    public function down()
    {
        Schema::table('kitchens', function (Blueprint $table) {
            if (Schema::hasColumn('kitchens', 'shop_id')) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            }
        });
    }
}