<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('order_returns', 'customer_id')) {
                $table->foreignId('customer_id')->after('order_id')
                      ->constrained('customers')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_returns', function (Blueprint $table) {
            if (Schema::hasColumn('order_returns', 'customer_id')) {
                $table->dropForeignKeyIfExists(['customer_id']);
                $table->dropColumn('customer_id');
            }
        });
    }
};
