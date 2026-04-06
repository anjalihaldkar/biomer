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
        Schema::table('product_variations', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('product_variations', 'unit')) {
                $table->string('unit')->nullable()->after('weight')->comment('Unit of measurement (kg, liter, etc)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            if (Schema::hasColumn('product_variations', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }
};
