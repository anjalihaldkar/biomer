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
        Schema::table('products', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit')->nullable()->default('kg')->after('base_price')->comment('Default unit of measurement (kg, liter, etc)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }
};
