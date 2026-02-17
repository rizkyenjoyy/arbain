<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jamaah;
use App\Models\Package; // Model untuk paket
use Illuminate\Http\Request;
use App\Notifications\NewJamaahAddedNotification;
use Illuminate\Support\Facades\Notification;

class JamaahController extends Controller
{
    public function index(Request $request)
    {
        $query = Jamaah::where('referensi_id', auth()->id())
                       ->where('status', 'pending'); // Hanya ambil jamaah dengan status pending

        // Search by Jamaah name or NIK
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $jamaahs = $query->paginate(10); // Menampilkan 10 jamaah per halaman

        return view('dashboard.jamaah.agen.index', compact('jamaahs'));
    }

    public function create()
    {
        // Menampilkan form pengajuan jamaah
        $packages = Package::all(); // Ambil semua paket
        return view('dashboard.jamaah.agen.create', compact('packages'));
    }

    public function store(Request $request)
    {
        // Validasi dan menyimpan jamaah baru
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_paspor' => 'required|string|max:255',
            'nik' => 'required|string|max:255', // Validasi NIK
            'tempat_lahir' => 'required|string|max:255', // Validasi Tempat Lahir
            'tanggal_lahir' => 'required|date', // Validasi Tanggal Lahir
            'jenis_kelamin' => 'required|in:L,P', // Validasi Jenis Kelamin
            'riwayat_penyakit' => 'nullable|string', // Validasi Riwayat Penyakit
            'alamat' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
        ]);

        $jamaah = Jamaah::create([
            'nama' => $request->nama,
            'no_paspor' => $request->no_paspor,
            'nik' => $request->nik, // Simpan NIK
            'tempat_lahir' => $request->tempat_lahir, // Simpan Tempat Lahir
            'tanggal_lahir' => $request->tanggal_lahir, // Simpan Tanggal Lahir
            'jenis_kelamin' => $request->jenis_kelamin, // Simpan Jenis Kelamin
            'riwayat_penyakit' => $request->riwayat_penyakit, // Simpan Riwayat Penyakit
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'referensi_id' => auth()->id(), // Menyimpan ID agen yang mengajukan
            'package_id' => $request->package_id,
        ]);

        // Kirim notifikasi berbasis database ke semua admin
        $adminUsers = User::role('Admin')->get(); // Ambil semua user dengan role admin
        Notification::send($adminUsers, new NewJamaahAddedNotification($jamaah));

        return redirect()->route('jamaah.agen.index')->with('success', 'Jamaah berhasil diajukan.');
    }

    public function destroy(Jamaah $jamaah)
    {
        // Menghapus jamaah jika statusnya masih pending
        if ($jamaah->status === 'pending') {
            $jamaah->delete();
            return redirect()->route('jamaah.agen.index')->with('success', 'Jamaah berhasil dihapus.');
        }

        return redirect()->route('jamaah.agen.index')->with('error', 'Jamaah sudah disetujui dan tidak bisa dihapus.');
    }
}
