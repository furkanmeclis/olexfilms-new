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
        Schema::create('product_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('location_type', ['merkez', 'bayi', 'depo', 'kargo', 'musteri']);
            $table->string('location_name')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->enum('quantity_type', ['m2', 'kutu', 'adet', 'kg', 'lt']);
            $table->decimal('used_quantity', 10, 2)->default(0);
            $table->decimal('remaining_quantity', 10, 2)->storedAs('quantity - used_quantity');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['product_id', 'location_type']);
            $table->index(['user_id', 'created_at']);
            $table->index(['code', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_codes');
    }
};
