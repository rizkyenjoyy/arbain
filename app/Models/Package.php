<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'departure_date',
        'price',
        'total_jamaah',  // Tambahkan ini
    ];


    // Relasi ke jamaah
    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class);
    }

    // Relasi ke payments (opsional, bisa diambil melalui jamaah)
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Jamaah::class);
    }
    // In app/Models/Package.php

    public function scopeLessThanEqualToTwelveJamaahs($query)
    {
        return $query->withCount('jamaahs')
                    ->having('jamaahs_count', '<', 12);
    }

}

