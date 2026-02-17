<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\Jamaah;

class CommissionsSeeder extends Seeder
{
    public function run()
    {
        // Ambil jamaah yang statusnya lunas
        $lunasJamaahs = Jamaah::whereHas('payments', function ($query) {
            $query->where('status', 'approved'); // Hanya ambil jamaah dengan pembayaran yang disetujui
        })->get()->filter(function ($jamaah) {
            return $jamaah->tagihan_status === 'lunas'; // Hanya jamaah yang lunas
        });

        // Jika ada jamaah yang memenuhi syarat, buat 20 komisi
        if ($lunasJamaahs->count() > 0) {
            $lunasJamaahs = $lunasJamaahs->shuffle(); // Acak jamaah untuk mendapatkan variasi

            for ($i = 0; $i < 20; $i++) {
                // Ambil jamaah secara acak
                $jamaah = $lunasJamaahs->random();

                // Buat komisi
                Commission::create([
                    'jamaah_id' => $jamaah->id,
                    'agen_id' => $jamaah->referensi_id, // Agen dari referensi_id
                    'amount' => rand(50000, 2000000), // Jumlah komisi random
                ]);
            }
        }
    }
}
