<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dealer extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'trade_name',
        'tax_number',
        'tax_office',
        'logo_path',
        'cover_image_path',
        'phone',
        'mobile',
        'email',
        'website',
        'address',
        'city',
        'district',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'location_name',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'tiktok_url',
        'description',
        'services',
        'working_hours',
        'established_year',
        'is_active',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Kullanıcı ilişkisi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Worker'lar ilişkisi
     */
    public function workers(): HasMany
    {
        return $this->hasMany(User::class, 'dealer_id');
    }

    /**
     * Bayi aktif mi?
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Bayi doğrulanmış mı?
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Tam adres
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->district,
            $this->city,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Sosyal medya linkleri
     */
    public function getSocialMediaLinksAttribute(): array
    {
        return array_filter([
            'facebook' => $this->facebook_url,
            'instagram' => $this->instagram_url,
            'twitter' => $this->twitter_url,
            'linkedin' => $this->linkedin_url,
            'youtube' => $this->youtube_url,
            'tiktok' => $this->tiktok_url,
        ]);
    }

    /**
     * Logo URL'i
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    /**
     * Kapak resmi URL'i
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image_path ? asset('storage/' . $this->cover_image_path) : null;
    }
}
