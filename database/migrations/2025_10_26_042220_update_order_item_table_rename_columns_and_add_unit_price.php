<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_item', function (Blueprint $table) {
            // Drop old foreign key constraints first
            $table->dropForeign(['order_id']);
            $table->dropForeign(['products_sku_id']);

            // Rename columns
            $table->renameColumn('order_id', 'order_detail_id');
            $table->renameColumn('products_sku_id', 'product_sku_id');
            $table->renameColumn('quantity', 'qty');

            // Add unit_price column
            if (!Schema::hasColumn('order_item', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->after('qty');
            }
        });

        // Re-add foreign key constraints with new column names
        Schema::table('order_item', function (Blueprint $table) {
            $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('cascade');
            $table->foreign('product_sku_id')->references('id')->on('products_skus')->onDelete('cascade');

            // Add indexes for performance
            $table->index('order_detail_id');
            $table->index('product_id');
            $table->index('product_sku_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item', function (Blueprint $table) {
            // Drop new foreign key constraints
            $table->dropForeign(['order_detail_id']);
            $table->dropForeign(['product_sku_id']);

            // Drop indexes
            $table->dropIndex(['order_detail_id']);
            $table->dropIndex(['product_id']);
            $table->dropIndex(['product_sku_id']);

            // Rename columns back
            $table->renameColumn('order_detail_id', 'order_id');
            $table->renameColumn('product_sku_id', 'products_sku_id');
            $table->renameColumn('qty', 'quantity');

            // Drop unit_price column
            if (Schema::hasColumn('order_item', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
        });

        // Re-add old foreign key constraints
        Schema::table('order_item', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('order_details')->onDelete('cascade');
            $table->foreign('products_sku_id')->references('id')->on('products_skus')->onDelete('cascade');
        });
    }
};
