<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCodeHistory extends Model
{
    protected $fillable = [
        'product_code_id',
        'user_id',
        'action_type',
        'quantity_before',
        'quantity_after',
        'quantity_change',
        'location_type_before',
        'location_type_after',
        'location_name_before',
        'location_name_after',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'quantity_before' => 'decimal:2',
        'quantity_after' => 'decimal:2',
        'quantity_change' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function productCode(): BelongsTo
    {
        return $this->belongsTo(ProductCode::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionDisplayAttribute(): string
    {
        $actions = [
            'created' => 'Oluşturuldu',
            'updated' => 'Güncellendi',
            'used' => 'Kullanıldı',
            'transferred' => 'Transfer Edildi',
            'received' => 'Alındı',
            'cancelled' => 'İptal Edildi',
        ];

        return $actions[$this->action_type] ?? $this->action_type;
    }

    public function getLocationDisplayBeforeAttribute(): string
    {
        $locationTypes = [
            'merkez' => 'Merkez',
            'bayi' => 'Bayi',
            'depo' => 'Depo',
            'kargo' => 'Kargo',
            'musteri' => 'Müşteri',
        ];

        $type = $locationTypes[$this->location_type_before] ?? $this->location_type_before;
        
        if ($this->location_name_before) {
            return $type . ' - ' . $this->location_name_before;
        }

        return $type;
    }

    public function getLocationDisplayAfterAttribute(): string
    {
        $locationTypes = [
            'merkez' => 'Merkez',
            'bayi' => 'Bayi',
            'depo' => 'Depo',
            'kargo' => 'Kargo',
            'musteri' => 'Müşteri',
        ];

        $type = $locationTypes[$this->location_type_after] ?? $this->location_type_after;
        
        if ($this->location_name_after) {
            return $type . ' - ' . $this->location_name_after;
        }

        return $type;
    }
}
