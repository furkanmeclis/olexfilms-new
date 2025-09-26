<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferItem extends Model
{
    protected $fillable = [
        'transfer_id',
        'product_code_id',
        'quantity',
        'quantity_type',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }

    public function productCode(): BelongsTo
    {
        return $this->belongsTo(ProductCode::class);
    }

    public function getFormattedQuantityAttribute(): string
    {
        return number_format((float) $this->quantity, 2) . ' ' . $this->quantity_type;
    }
}
