<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // This is a dummy migration to mark the coupons table creation as "migrated"
    // The table already exists in the database from previous setup
    
    public function up(): void
    {
        // Coupons table already exists - do nothing
        // This migration just marks the batch as completed
    }

    public function down(): void
    {
        // Don't drop table - it already existed before migration system
    }
};
