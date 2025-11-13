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
        Schema::table('order_details', function (Blueprint $table) {
            // Change total from integer to decimal
            $table->decimal('total', 10, 2)->default(0)->change();

            // Add status column
            if (!Schema::hasColumn('order_details', 'status')) {
                $table->string('status')->default('pending')->after('total');
            }

            // Add indexes for performance
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Revert total back to integer
            $table->integer('total')->change();

            // Drop status column
            if (Schema::hasColumn('order_details', 'status')) {
                $table->dropColumn('status');
            }

            // Drop indexes
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });
    }
};
