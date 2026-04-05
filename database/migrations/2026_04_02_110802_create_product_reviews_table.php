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
        // This migration is superseded by 2026_04_04_000000_create_product_reviews_table.php
        // Do nothing - the actual table creation happens in the later migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing - don't drop anything since we didn't create it
    }
};
