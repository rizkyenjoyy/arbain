<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'nik',
        'password',
        'last_activity',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation to Jamaah
     */
    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'referensi_id');
    }

    /**
     * Relation to Commission
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'agen_id');
    }

    /**
     * Scope to filter active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope to filter inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }
}
