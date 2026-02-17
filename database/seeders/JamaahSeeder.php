<?php

namespace Database\Seeders;

use App\Models\Jamaah;
use App\Models\Package;
use Illuminate\Database\Seeder;

class JamaahSeeder extends Seeder
{
    public function run()
    {
        $packages = Package::all(); // Ambil semua paket untuk dihubungkan ke Jamaah

        // Buat 20 Jamaah dengan status pending
        foreach (range(1, 20) as $index) {
            Jamaah::create([
                'nama' => "Jamaah Pending $index",
                'no_paspor' => 'A' . str_pad($index, 6, '0', STR_PAD_LEFT),
                'nik' => '123456789012345' . $index,
                'tempat_lahir' => 'Kota ' . $index,
                'tanggal_lahir' => now()->subYears(20 + $index)->format('Y-m-d'),
                'jenis_kelamin' => $index % 2 == 0 ? 'L' : 'P',
                'riwayat_penyakit' => null,
                'alamat' => 'Alamat Jamaah ' . $index,
                'kontak' => '08123456789' . $index,
                'status' => 'pending',
                'referensi_id' => 4, // Ubah sesuai dengan user referensi
                'package_id' => $packages->random()->id, // Ambil paket secara acak
                'total_amount' => 0, // Jumlah pembayaran awal 0
            ]);
        }
    }
}
