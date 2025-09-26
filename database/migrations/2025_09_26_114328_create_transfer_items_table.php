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
        Schema::create('transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_code_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->enum('quantity_type', ['m2', 'kutu', 'adet', 'kg', 'lt']);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['transfer_id', 'product_code_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_items');
    }
};
