<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jamaah;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Ambil jamaah yang statusnya approved dan belum lunas
        $jamaahs = Jamaah::where('status', 'approved')->get();

        foreach ($jamaahs as $jamaah) {
            // Membuat beberapa pembayaran untuk tiap jamaah
            Payment::create([
                'jamaah_id' => $jamaah->id,
                'amount' => rand(10000, 100000), // Jumlah random sebagai contoh
                'payment_method' => 'transfer',
                'payment_proof' => null,
                'status' => 'pending',
            ]);

            Payment::create([
                'jamaah_id' => $jamaah->id,
                'amount' => rand(10000, 100000), // Jumlah random sebagai contoh
                'payment_method' => 'cash',
                'payment_proof' => null,
                'status' => 'approved',
            ]);
        }
    }
}
