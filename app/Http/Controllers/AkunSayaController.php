<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkunSayaController extends Controller
{
    public function index()
    {
        $admin = Auth::user(); // Ambil data admin yang sedang login
        return view('dashboard.akun_saya.index', compact('admin'));
    }
    public function agenIndex()
    {
        // Ambil agen yang sedang login
        $agen = auth()->user(); // Asumsi agen menggunakan sistem otentikasi
        // Ambil riwayat komisi untuk agen tersebut
        $riwayatKomisi = $agen->commissions; // Mengambil semua komisi yang terkait dengan agen

        // Hitung total komisi
        $totalKomisi = $riwayatKomisi->sum('amount');

        return view('dashboard.akun_saya.agen', compact('agen', 'riwayatKomisi', 'totalKomisi'));
    }

}
