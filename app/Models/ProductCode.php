<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductCode extends Model
{
    protected $fillable = [
        'code',
        'product_id',
        'user_id',
        'location_type',
        'location_name',
        'quantity',
        'quantity_type',
        'used_quantity',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'used_quantity' => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productCode) {
            if (empty($productCode->code)) {
                $productCode->code = 'PC-' . strtoupper(Str::random(8));
            }
        });

        static::saved(function ($productCode) {
            // History kaydı oluştur
            $productCode->createHistoryRecord();
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ProductCodeHistory::class);
    }

    public function transferItems(): HasMany
    {
        return $this->hasMany(TransferItem::class);
    }

    public function getRemainingQuantityAttribute(): float
    {
        return $this->quantity - $this->used_quantity;
    }

    public function getFormattedQuantityAttribute(): string
    {
        return number_format((float) $this->quantity, 2) . ' ' . $this->quantity_type;
    }

    public function getFormattedRemainingQuantityAttribute(): string
    {
        return number_format((float) $this->remaining_quantity, 2) . ' ' . $this->quantity_type;
    }

    public function getLocationDisplayAttribute(): string
    {
        $locationTypes = [
            'merkez' => 'Merkez',
            'bayi' => 'Bayi',
            'depo' => 'Depo',
            'kargo' => 'Kargo',
            'musteri' => 'Müşteri',
        ];

        $type = $locationTypes[$this->location_type] ?? $this->location_type;
        
        if ($this->location_name) {
            return $type . ' - ' . $this->location_name;
        }

        return $type;
    }

    public function useQuantity(float $amount, string $notes = null): bool
    {
        if ($this->remaining_quantity < $amount) {
            return false;
        }

        $oldQuantity = (float) $this->used_quantity;
        $this->used_quantity = $oldQuantity + $amount;
        $this->save();

        // History kaydı oluştur
        $this->createHistoryRecord('used', $oldQuantity, $this->used_quantity, $amount, $notes);

        return true;
    }

    public function transferTo(string $newLocationType, string $newLocationName = null, string $notes = null): bool
    {
        $oldLocationType = $this->location_type;
        $oldLocationName = $this->location_name;

        $this->location_type = $newLocationType;
        $this->location_name = $newLocationName;
        $this->save();

        // History kaydı oluştur
        $this->createHistoryRecord('transferred', $this->used_quantity, $this->used_quantity, 0, $notes, [
            'location_type_before' => $oldLocationType,
            'location_name_before' => $oldLocationName,
            'location_type_after' => $newLocationType,
            'location_name_after' => $newLocationName,
        ]);

        return true;
    }

    protected function createHistoryRecord(string $actionType = 'updated', float $quantityBefore = null, float $quantityAfter = null, float $quantityChange = 0, string $notes = null, array $metadata = []): void
    {
        $this->histories()->create([
            'user_id' => auth()->id(),
            'action_type' => $actionType,
            'quantity_before' => $quantityBefore ?? $this->used_quantity,
            'quantity_after' => $quantityAfter ?? $this->used_quantity,
            'quantity_change' => $quantityChange,
            'location_type_before' => $this->getOriginal('location_type'),
            'location_type_after' => $this->location_type,
            'location_name_before' => $this->getOriginal('location_name'),
            'location_name_after' => $this->location_name,
            'notes' => $notes,
            'metadata' => $metadata,
        ]);
    }
}
