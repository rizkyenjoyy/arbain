<?php

namespace Database\Seeders;

use App\Models\Jamaah;
use App\Models\Package;
use Illuminate\Database\Seeder;

class JamaahSeeder extends Seeder
{
    public function run()
    {
        $packages = Package::all();
        if ($packages->isEmpty()) {
            return;
        }

        $agenIds = \App\Models\User::role('Agen')->pluck('id')->toArray();
        if (empty($agenIds)) {
            $agenIds = \App\Models\User::pluck('id')->toArray();
        }

        // Buat 10 Jamaah pending
        foreach (range(1, 10) as $index) {
            $package = $packages->random();
            Jamaah::create([
                'nama' => "Jamaah Pending $index",
                'no_paspor' => 'A' . str_pad($index, 6, '0', STR_PAD_LEFT),
                'nik' => '32011234567890' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $index,
                'tanggal_lahir' => now()->subYears(20 + $index)->format('Y-m-d'),
                'jenis_kelamin' => $index % 2 == 0 ? 'L' : 'P',
                'riwayat_penyakit' => null,
                'alamat' => 'Alamat Jamaah ' . $index,
                'kontak' => '0812345678' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'status' => 'pending',
                'referensi_id' => $agenIds[array_rand($agenIds)],
                'package_id' => $package->id,
                'total_amount' => 0,
            ]);
        }

        // Buat 10 Jamaah approved & lunas
        foreach (range(11, 20) as $index) {
            $package = $packages->random();
            Jamaah::create([
                'nama' => "Jamaah Approved $index",
                'no_paspor' => 'B' . str_pad($index, 6, '0', STR_PAD_LEFT),
                'nik' => '32011234567890' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Kota ' . $index,
                'tanggal_lahir' => now()->subYears(20 + $index)->format('Y-m-d'),
                'jenis_kelamin' => $index % 2 == 0 ? 'L' : 'P',
                'riwayat_penyakit' => null,
                'alamat' => 'Alamat Jamaah ' . $index,
                'kontak' => '0812345678' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'status' => 'approved',
                'referensi_id' => $agenIds[array_rand($agenIds)],
                'package_id' => $package->id,
                'total_amount' => $package->price,
            ]);
        }
    }
}
