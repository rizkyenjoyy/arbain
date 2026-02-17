<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'riwayat_penyakit',
        'no_paspor',
        'alamat',
        'kontak',
        'status',
        'referensi_id',
        'package_id',
        'amount',
        'total_amount',
        'processed_by',

    ];

    // Relasi ke Agen (User)
    public function referensi()
    {
        return $this->belongsTo(User::class, 'referensi_id');
    }

    // Relasi ke Paket
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relasi ke Pembayaran
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relasi ke Komisi
    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    // Cek status lunas
    public function getTagihanStatusAttribute()
    {
        return $this->total_amount >= $this->package->price ? 'lunas' : 'belum lunas';
    }

     // Cek jatuh tempo pembayaran (30 hari sebelum keberangkatan)
     public function getJatuhTempoAttribute()
     {
         // Pastikan departure_date ada dan merupakan objek Carbon
         if ($this->package && $this->package->departure_date) {
             return Carbon::parse($this->package->departure_date)->subDays(30);
         }

         return null; // Jika tidak ada tanggal keberangkatan, kembalikan null
     }

    // Hitung sisa pembayaran (tunggakan)
    public function getSisaPembayaranAttribute()
    {
        return $this->package->price - $this->total_amount;
    }

    public function agen()
    {
        return $this->belongsTo(User::class, 'referensi_id'); // Sesuaikan jika kolom berbeda
    }

    // Tambahkan fungsi Urgent (90 hari sebelum keberangkatan)
    public function getUrgentStatusAttribute()
    {
        // Pastikan departure_date adalah objek Carbon
        $departureDate = Carbon::parse($this->package->departure_date);

        // Cek jika sekarang sudah dalam 90 hari sebelum keberangkatan
        return Carbon::now()->greaterThanOrEqualTo($departureDate->subDays(90));
    }
    // app/Models/Jamaah.php
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    // app/Models/Jamaah.php

    public function scopeBelumLunasDiH60($query)
    {
        return $query->join('packages', 'jamaahs.package_id', '=', 'packages.id')
            ->whereDate('packages.departure_date', '<=', Carbon::now()->addDays(60))
            ->whereColumn('jamaahs.total_amount', '<', 'packages.price');
    }



}
