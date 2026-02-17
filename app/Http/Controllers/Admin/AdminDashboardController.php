<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\User;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Commission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Dapatkan tahun yang dipilih dari request atau tahun saat ini sebagai default
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Total Jamaah
        $totalJamaah = Jamaah::count();

        $totalJamaahPending = Jamaah::where('status', 'pending')->count();

        $totalJamaahUnpaid = Jamaah::where('total_amount', 0)->count();


        // Total Agen (dengan role Agen)
        $totalAgen = User::role('Agen')->count();

        // Pembayaran yang sudah diterima (status 'approved')
        $approvedPayments = Payment::where('status', 'approved')->count();

        // Komisi yang sudah dibayarkan (status 'paid' di tabel commissions)
        $paidCommissions = Commission::count();

        // Pembayaran yang sedang pending (status 'pending')
        $pendingPayments = Payment::where('status', 'pending')->count();

        $totalJamaahKejarLunas = Jamaah::belumLunasDiH60()->count();

        $packagesWithFewerJamaah = Package::lessThanEqualToTwelveJamaahs()->count();



        // Total transaksi per bulan berdasarkan tahun yang dipilih
        $transactions = Payment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->whereYear('created_at', $selectedYear)  // Filter berdasarkan tahun yang dipilih
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

        return view('dashboard.admin.index', compact('totalJamaah', 'totalAgen', 'approvedPayments', 'paidCommissions', 'monthlyTransactions', 'availableYears', 'selectedYear', 'pendingPayments', 'totalJamaahPending', 'totalJamaahUnpaid', 'totalJamaahKejarLunas', 'packagesWithFewerJamaah'));
    }
}
