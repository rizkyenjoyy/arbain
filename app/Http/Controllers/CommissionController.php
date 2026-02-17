<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Jamaah;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Notifications\CommissionAddedNotification;

class CommissionController extends Controller
{
    // Menampilkan daftar komisi untuk admin
    public function index(Request $request)
    {
        // Ambil query parameter untuk filter
        $jamaahName = $request->input('jamaah_name');
        $agenName = $request->input('agen_name');

        // Query untuk mengambil komisi dengan relasi jamaah dan agen
        $commissions = Commission::with(['jamaah', 'agen']);

        // Filter berdasarkan nama jamaah jika ada
        if ($jamaahName) {
            $commissions->whereHas('jamaah', function ($query) use ($jamaahName) {
                $query->where('nama', 'like', '%' . $jamaahName . '%');
            });
        }

        // Filter berdasarkan nama agen jika ada
        if ($agenName) {
            $commissions->whereHas('agen', function ($query) use ($agenName) {
                $query->where('name', 'like', '%' . $agenName . '%');
            });
        }


        // Ambil data yang sudah difilter
        $commissions = $commissions->paginate(10);

        return view('dashboard.commissions.admin.index', compact('commissions'));
    }


    // Menampilkan form untuk membuat komisi baru
    public function create()
    {
        // Mengambil jamaah yang sudah lunas
        $jamaahs = Jamaah::whereHas('payments', function ($query) {
            $query->where('status', 'approved'); // Hanya jamaah yang memiliki pembayaran approved
        })->get()->filter(function ($jamaah) {
            return $jamaah->tagihan_status === 'lunas'; // Memastikan hanya jamaah yang statusnya lunas
        });

        return view('dashboard.commissions.admin.create', compact('jamaahs'));
    }


    // Menyimpan komisi baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Cari jamaah terkait
        $jamaah = Jamaah::findOrFail($request->jamaah_id);

        // Cek apakah jamaah sudah memiliki komisi
        if (Commission::where('jamaah_id', $jamaah->id)->exists()) {
            return redirect()->back()->withErrors(['jamaah_id' => 'Jamaah ini sudah memiliki komisi.']);
        }

        // Buat komisi baru
        $commission = Commission::create([
            'jamaah_id' => $jamaah->id,
            'agen_id' => $jamaah->referensi_id, // Agen dari referensi_id
            'amount' => $request->amount,
            'status' => 'pending',
        ]);
        // Kirim notifikasi ke agen terkait
        $agen = User::find($jamaah->referensi_id); // Mengambil agen berdasarkan referensi_id
        if ($agen) {
            $agen->notify(new CommissionAddedNotification($commission)); // Kirim notifikasi
        }
        return redirect()->route('commissions.index')->with('success', 'Komisi berhasil dibuat.');
    }


    // Menghapus komisi dari database
    public function destroy(Commission $commission)
    {
        $commission->delete();
        return redirect()->route('commissions.index')->with('success', 'Komisi berhasil dihapus.');
    }

    // Export to Excel for admin
    public function exportExcel(Request $request)
    {
        $jamaahName = $request->input('jamaah_name');
        $agenName = $request->input('agen_name');

        $commissions = Commission::with(['jamaah', 'agen']);

        if ($jamaahName) {
            $commissions->whereHas('jamaah', function ($query) use ($jamaahName) {
                $query->where('nama', 'like', '%' . $jamaahName . '%');
            });
        }

        if ($agenName) {
            $commissions->whereHas('agen', function ($query) use ($agenName) {
                $query->where('name', 'like', '%' . $agenName . '%');
            });
        }

        $commissions = $commissions->get();

        // Define the headers for the Excel export
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment;filename="komisi_agen_admin.xls"',
            'Cache-Control' => 'max-age=0',
        ];

        // Generate the Excel content using a Blade view
        $content = view('dashboard.commissions.admin.excel', compact('commissions'))->render();

        return Response::make($content, 200, $headers);
    }

}
