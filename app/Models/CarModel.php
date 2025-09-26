<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CarModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'name',
        'external_id',
        'last_update',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_update' => 'datetime',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    public function getRouteKeyName(): string
    {
        return 'external_id';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->external_id)) {
                $model->external_id = Str::slug($model->name) . '-' . strtoupper(Str::random(6));
            }
        });
    }
}
