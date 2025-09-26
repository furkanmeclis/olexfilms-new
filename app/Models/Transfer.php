<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transfer extends Model
{
    protected $fillable = [
        'transfer_number',
        'from_user_id',
        'to_user_id',
        'status',
        'cargo_code',
        'cargo_company',
        'notes',
        'sent_at',
        'delivered_at',
        'created_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transfer) {
            if (empty($transfer->transfer_number)) {
                $transfer->transfer_number = 'TR-' . strtoupper(Str::random(8));
            }
        });

        static::saved(function ($transfer) {
            // Transfer durumu değiştiğinde history kaydı oluştur
            if ($transfer->wasChanged('status')) {
                $transfer->createTransferHistoryRecord();
            }
        });
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransferItem::class);
    }

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'beklemede' => 'Beklemede',
            'yolda' => 'Yolda',
            'teslim_edildi' => 'Teslim Edildi',
            'iptal' => 'İptal',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'beklemede' => 'warning',
            'yolda' => 'info',
            'teslim_edildi' => 'success',
            'iptal' => 'danger',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function markAsSent(string $cargoCode = null, string $cargoCompany = null): bool
    {
        $this->status = 'yolda';
        $this->cargo_code = $cargoCode;
        $this->cargo_company = $cargoCompany;
        $this->sent_at = now();
        return $this->save();
    }

    public function markAsDelivered(): bool
    {
        $this->status = 'teslim_edildi';
        $this->delivered_at = now();
        return $this->save();
    }

    public function cancel(): bool
    {
        $this->status = 'iptal';
        return $this->save();
    }

    protected function createTransferHistoryRecord(): void
    {
        $oldStatus = $this->getOriginal('status');
        $newStatus = $this->status;
        
        $statusLabels = [
            'beklemede' => 'Beklemede',
            'yolda' => 'Yolda',
            'teslim_edildi' => 'Teslim Edildi',
            'iptal' => 'İptal',
        ];

        // Transfer kalemleri için history kaydı oluştur
        foreach ($this->items as $item) {
            $item->productCode->histories()->create([
                'user_id' => auth()->id(),
                'action_type' => 'transferred',
                'quantity_before' => $item->productCode->used_quantity,
                'quantity_after' => $item->productCode->used_quantity,
                'quantity_change' => 0,
                'location_type_before' => $item->productCode->location_type,
                'location_type_after' => $item->productCode->location_type,
                'location_name_before' => $item->productCode->location_name,
                'location_name_after' => $item->productCode->location_name,
                'notes' => "Transfer durumu güncellendi: {$statusLabels[$oldStatus]} → {$statusLabels[$newStatus]}",
                'metadata' => [
                    'transfer_id' => $this->id,
                    'transfer_number' => $this->transfer_number,
                    'status_before' => $oldStatus,
                    'status_after' => $newStatus,
                    'from_user' => $this->fromUser->name,
                    'to_user' => $this->toUser->name,
                ],
            ]);
        }
    }
}
