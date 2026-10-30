<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jamaah;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Ambil jamaah yang statusnya approved
        $jamaahs = Jamaah::where('status', 'approved')->with('package')->get();

        foreach ($jamaahs as $jamaah) {
            $halfPrice = $jamaah->package ? ($jamaah->package->price / 2) : 5000000;

            // Membuat 2 pembayaran disetujui sehingga lunas
            Payment::create([
                'jamaah_id' => $jamaah->id,
                'amount' => $halfPrice,
                'payment_method' => 'transfer',
                'payment_proof' => null,
                'status' => 'approved',
            ]);

            Payment::create([
                'jamaah_id' => $jamaah->id,
                'amount' => $halfPrice,
                'payment_method' => 'cash',
                'payment_proof' => null,
                'status' => 'approved',
            ]);
        }
    }
}
