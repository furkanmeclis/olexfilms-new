<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dealer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Dealer ilişkisi - Worker'ların bağlı olduğu dealer
     */
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    /**
     * Workers ilişkisi - Dealer'ın sahip olduğu worker'lar
     */
    public function workers()
    {
        return $this->hasMany(User::class, 'dealer_id');
    }

    /**
     * Dealer profili ilişkisi
     */
    public function dealerProfile()
    {
        return $this->hasOne(Dealer::class);
    }

    /**
     * Kullanıcının dealer olup olmadığını kontrol et
     */
    public function isDealer(): bool
    {
        return $this->hasRole('dealer');
    }

    /**
     * Kullanıcının worker olup olmadığını kontrol et
     */
    public function isWorker(): bool
    {
        return $this->hasRole('worker');
    }
}
