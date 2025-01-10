<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOrdersTableRefinedSecondApproach extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1) If there's a foreign key constraint on 'waiter_id', drop it first
            if (Schema::hasColumn('orders', 'waiter_id')) {
                // Drop the foreign key constraint if it exists
                // (Often named 'orders_waiter_id_foreign', but might differ)
                $table->dropForeign(['waiter_id']);

                // Then drop the column
                $table->dropColumn('waiter_id');
            }

            // If you also had 'orderitem_id' in orders, drop it similarly:
            if (Schema::hasColumn('orders', 'orderitem_id')) {
                $table->dropForeign(['orderitem_id']);
                $table->dropColumn('orderitem_id');
            }

            // Ensure 'customer_id' column exists and references 'customers(id)'
            // If it’s already there and correct, you can skip this step.
            if (!Schema::hasColumn('orders', 'customer_id')) {
                $table->foreignId('customer_id')
                      ->nullable()
                      ->constrained('customers')
                      ->onDelete('cascade');
            }

            // Ensure there's a 'status' column
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // On rollback, you can re-add the columns if you want:
            
            // if (!Schema::hasColumn('orders', 'waiter_id')) {
            //     $table->unsignedBigInteger('waiter_id')->nullable();
            //     // If you want to restore the FK:
            //     // $table->foreign('waiter_id')->references('id')->on('waiters')->onDelete('cascade');
            // }
            
            // if (!Schema::hasColumn('orders', 'orderitem_id')) {
            //     $table->foreignId('orderitem_id')->nullable()->constrained('order_items')->onDelete('cascade');
            // }
            
            // If you want to remove 'customer_id' or 'status' on rollback, do so here:
            // if (Schema::hasColumn('orders', 'customer_id')) {
            //     $table->dropForeign(['customer_id']);
            //     $table->dropColumn('customer_id');
            // }
            // if (Schema::hasColumn('orders', 'status')) {
            //     $table->dropColumn('status');
            // }
        });
    }
}