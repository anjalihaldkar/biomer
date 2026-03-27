<?php
// ── FILE 1: database/migrations/xxxx_create_orders_table.php ─────────
// Run: php artisan make:migration create_orders_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['pending','confirmed','processing','shipped','delivered','cancelled'])
                  ->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};


// ── FILE 2: database/migrations/xxxx_create_order_items_table.php ────
// Run: php artisan make:migration create_order_items_table

// return new class extends Migration {
//     public function up(): void {
//         Schema::create('order_items', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('order_id')->constrained()->cascadeOnDelete();
//             $table->unsignedBigInteger('product_id')->nullable();
//             $table->unsignedBigInteger('variation_id')->nullable();
//             $table->string('name');
//             $table->string('sku')->nullable();
//             $table->decimal('price', 10, 2);
//             $table->integer('quantity');
//             $table->decimal('subtotal', 10, 2);
//             $table->timestamps();
//         });
//     }
//     public function down(): void { Schema::dropIfExists('order_items'); }
// };