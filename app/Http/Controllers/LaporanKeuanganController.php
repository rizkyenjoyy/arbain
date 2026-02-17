<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan tahun dan bulan yang dipilih dari request, atau gunakan tahun dan bulan sekarang sebagai default
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('m')); // Tambahkan ini

        // Hitung Total Pemasukan dari tabel Jamaah
        $totalPemasukan = Jamaah::sum('total_amount');

        // Hitung Total Komisi
        $totalKomisi = Commission::sum('amount');

        // Hitung Total Profit (Total Pemasukan - Total Komisi)
        $totalProfit = $totalPemasukan - $totalKomisi;

        // Hitung Total Pengeluaran dari tabel commissions
        $totalPengeluaran = Commission::sum('amount');

        // Hitung Jamaah yang Pending berdasarkan status pembayaran di tabel payments
        $pendingPayments = DB::table('payments')
                            ->where('status', 'pending')
                            ->count();

        // **Mengambil data pemasukan per bulan untuk tahun yang dipilih**
        $months = [];
        $monthlyIncomeData = [];

        // Looping untuk 12 bulan
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->translatedFormat('F'); // Mengambil nama bulan dalam bahasa Indonesia

            // Menghitung total pemasukan (ditambah komisi dan uang persediaan) per bulan
            $monthlyIncomeData[] = DB::table('payments')
                                     ->whereYear('created_at', $selectedYear)
                                     ->whereMonth('created_at', $i)
                                     ->sum('amount');
        }

        // Mengambil daftar tahun dari kolom created_at di tabel payments
        $years = DB::table('payments')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('laporan-keuangan.index', compact(
            'totalPemasukan',
            'totalProfit',
            'totalKomisi',
            'totalPengeluaran',
            'pendingPayments',
            'months',
            'monthlyIncomeData',
            'years',
            'selectedYear',
            'selectedMonth' // Tambahkan ini
        ));
    }


    public function downloadCsv(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        // Ambil data jamaah dengan filter tahun dan bulan yang dipilih
        $jamaahData = Jamaah::whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->with('referensi') // Ambil data jamaah beserta referensi
                            ->get();

        // Buat header CSV
        $csvData = "Nama,Jumlah Pembayaran,Tanggal Pembayaran,NIK,Referensi ID,Jenis Kelamin,Riwayat Penyakit,Tanggal Lahir,Alamat\n";

        foreach ($jamaahData as $jamaah) {
            $csvData .= "{$jamaah->nama},{$jamaah->total_amount},{$jamaah->created_at},{$jamaah->nik},{$jamaah->referensi->name},{$jamaah->jenis_kelamin},{$jamaah->riwayat_penyakit},{$jamaah->tanggal_lahir},{$jamaah->alamat}\n";
        }

        // Buat response CSV
        $response = response()->stream(
            function () use ($csvData) {
                echo $csvData;
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="laporan_jamaah.csv"',
            ]
        );

        return $response;
    }
}
