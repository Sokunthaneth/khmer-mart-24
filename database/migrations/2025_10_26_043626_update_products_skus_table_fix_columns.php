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
        Schema::table('products_skus', function (Blueprint $table) {
            // Change price from string to decimal
            $table->decimal('price', 10, 2)->change();

            // Rename quantity to stock
            $table->renameColumn('quantity', 'stock');

            // Add attributes column for storing JSON data (size, color, etc.)
            if (!Schema::hasColumn('products_skus', 'attributes')) {
                $table->json('attributes')->nullable()->after('stock');
            }

            // Add indexes for performance
            $table->index('product_id');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_skus', function (Blueprint $table) {
            // Revert price back to string
            $table->string('price')->change();

            // Rename stock back to quantity
            $table->renameColumn('stock', 'quantity');

            // Drop attributes column
            if (Schema::hasColumn('products_skus', 'attributes')) {
                $table->dropColumn('attributes');
            }

            // Drop indexes
            $table->dropIndex(['product_id']);
            $table->dropIndex(['sku']);
        });
    }
};
