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
        Schema::create('product_code_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_code_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('action_type', ['created', 'updated', 'used', 'transferred', 'received', 'cancelled']);
            $table->decimal('quantity_before', 10, 2);
            $table->decimal('quantity_after', 10, 2);
            $table->decimal('quantity_change', 10, 2);
            $table->enum('location_type_before', ['merkez', 'bayi', 'depo', 'kargo', 'musteri'])->nullable();
            $table->enum('location_type_after', ['merkez', 'bayi', 'depo', 'kargo', 'musteri'])->nullable();
            $table->string('location_name_before')->nullable();
            $table->string('location_name_after')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['product_code_id', 'action_type']);
            $table->index(['user_id', 'created_at']);
            $table->index(['action_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_code_histories');
    }
};
