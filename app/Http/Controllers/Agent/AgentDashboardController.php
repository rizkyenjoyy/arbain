<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Payment;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Total Jamaah yang didaftarkan oleh agen
        $totalJamaah = Jamaah::where('referensi_id', $userId)->count();

        // Komisi yang sudah dibayarkan untuk jamaah yang terkait
        $paidCommissions = Commission::whereHas('jamaah', function ($query) use ($userId) {
            $query->where('referensi_id', $userId);
        })->count();


        // Dapatkan tahun yang dipilih dari request atau tahun saat ini sebagai default
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Total transaksi per bulan berdasarkan tahun yang dipilih
        $transactions = Payment::selectRaw('YEAR(payments.created_at) as year, MONTH(payments.created_at) as month, COUNT(*) as total') // Specify payments.created_at
            ->join('jamaahs', 'payments.jamaah_id', '=', 'jamaahs.id') // Join dengan tabel jamaahs
            ->where('jamaahs.referensi_id', $userId)  // Filter berdasarkan referensi ID agen
            ->whereYear('payments.created_at', $selectedYear)  // Filter berdasarkan tahun yang dipilih
            ->groupBy('year', 'month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Menambahkan nol untuk bulan yang tidak memiliki data
        $monthlyTransactions = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyTransactions[$i] = $transactions[$i] ?? 0;
        }

        // Dapatkan daftar tahun yang tersedia di tabel pembayaran
        $availableYears = Payment::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('dashboard.agen.index', compact('totalJamaah', 'paidCommissions', 'monthlyTransactions', 'availableYears', 'selectedYear'));
    }
}
