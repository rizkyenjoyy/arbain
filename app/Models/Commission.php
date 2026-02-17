<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'agen_id',
        'amount',
    ];

    // Relasi ke Jamaah
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }


    // Relasi ke Agen (User)
    public function agen()
    {
        return $this->belongsTo(User::class, 'agen_id');
    }

    // Scope untuk komisi yang sudah "paid"
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // Scope untuk komisi yang masih "pending"
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
