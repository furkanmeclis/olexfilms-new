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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            
            // Kullanıcı ilişkisi
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Temel bilgiler
            $table->string('company_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_office')->nullable();
            
            // Logo ve görsel
            $table->string('logo_path')->nullable();
            $table->string('cover_image_path')->nullable();
            
            // İletişim bilgileri
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Adres bilgileri
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Türkiye');
            
            // Konum bilgileri
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_name')->nullable(); // Örn: "Merkez Ofis", "Şube 1"
            
            // Sosyal medya
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            
            // İş bilgileri
            $table->text('description')->nullable();
            $table->text('services')->nullable(); // Hizmetler
            $table->text('working_hours')->nullable(); // Çalışma saatleri
            $table->string('established_year')->nullable(); // Kuruluş yılı
            
            // Durum
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
