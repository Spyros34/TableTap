<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOrderItemsTableRefinedSecondApproach extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Ensure the 'order_id' column exists and references 'orders(id)'
            if (!Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')
                      ->nullable()
                      ->constrained('orders')
                      ->onDelete('cascade');
            }

        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // E.g. to revert the changes:
            if (Schema::hasColumn('order_items', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            if (Schema::hasColumn('order_items', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('order_items', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }
}