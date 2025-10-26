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
        Schema::create('products_skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_attribute_id')->nullable()->constrained('product_attributes')->onDelete('set null');
            $table->foreignId('color_attribute_id')->nullable()->constrained('product_attributes')->onDelete('set null');
            $table->string('sku');
            $table->string('price');
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_skus');
    }
};
