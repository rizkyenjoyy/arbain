<?php

namespace Database\Seeders;

use App\Models\Jamaah;
use Illuminate\Database\Seeder;

class JamaahjSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Jamaah::create([
                'nama' => 'Jamaah Approved ' . $i,
                'no_paspor' => 'P0000' . $i,
                'nik' => '1234567890' . $i,
                'tempat_lahir' => 'Tempat Lahir ' . $i,
                'tanggal_lahir' => now()->subYears(20 + $i)->format('Y-m-d'),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'riwayat_penyakit' => null,
                'alamat' => 'Alamat Jamaah ' . $i,
                'kontak' => '08123456789' . $i,
                'status' => 'approved',
                'referensi_id' => 3,
                'package_id' => 1,
                'total_amount' => 80000000,
            ]);
        }
    }
}
