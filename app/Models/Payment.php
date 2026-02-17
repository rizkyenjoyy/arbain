<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'amount',
        'payment_proof',
        'payment_method',
        'status',
        'processed_by',
    ];

    // Relasi ke jamaah
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    // Fungsi untuk validasi pembayaran transfer (membutuhkan bukti)
    public function getNeedsProofAttribute()
    {
        return $this->payment_method === 'transfer';
    }

    // Cek status pembayaran (pending, approved, rejected)
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    // app/Models/Payment.php

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

}
