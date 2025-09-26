<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CarBrand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'external_id',
        'logo',
        'last_update',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_update' => 'datetime',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_id');
    }

    public function getRouteKeyName(): string
    {
        return 'external_id';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->external_id)) {
                $brand->external_id = Str::slug($brand->name) . '-' . strtoupper(Str::random(6));
            }
        });
    }
}
