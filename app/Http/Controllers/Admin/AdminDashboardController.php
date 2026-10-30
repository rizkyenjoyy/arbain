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
        $selectedYear = (int) $request->input('year', Carbon::now()->year);

        // --- 1. METRIK FINANSIAL ---
        $totalRevenue = Payment::where('status', 'approved')->sum('amount');
        $thisMonthRevenue = Payment::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $paidCommissions = Commission::sum('amount');

        // Total Piutang / Kekurangan pembayaran dari jamaah approved
        $totalReceivables = Jamaah::where('status', 'approved')
            ->with('package')
            ->get()
            ->sum(function ($jamaah) {
                $price = $jamaah->package ? $jamaah->package->price : 0;
                return max(0, $price - $jamaah->total_amount);
            });

        // --- 2. METRIK OPERASIONAL & PERSETUJUAN ---
        $totalJamaah = Jamaah::count();
        $totalJamaahApproved = Jamaah::where('status', 'approved')->count();
        $totalJamaahPending = Jamaah::where('status', 'pending')->count();
        $totalJamaahUnpaid = Jamaah::where('total_amount', 0)->count();
        $totalJamaahKejarLunas = Jamaah::belumLunasDiH60()->count();

        $totalAgen = User::role('Agen')->count();
        $totalAgenActive = User::role('Agen')->where('is_active', 1)->count();

        $approvedPayments = Payment::where('status', 'approved')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        $totalPackages = Package::count();
        $packagesWithFewerJamaah = Package::lessThanEqualToTwelveJamaahs()->count();

        // --- 3. DATA GRAFIK BULANAN ---
        $transactionsQuery = Payment::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(CASE WHEN status = "approved" THEN amount ELSE 0 END) as revenue')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthlyTransactions = [];
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyTransactions[$i] = (int) ($transactionsQuery[$i]->count ?? 0);
            $monthlyRevenue[$i] = (float) ($transactionsQuery[$i]->revenue ?? 0);
        }

        // Dapatkan daftar tahun yang tersedia di tabel pembayaran
        $availableYears = Payment::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [Carbon::now()->year];
        }

        // Distribusi Metode Pembayaran
        $paymentMethods = Payment::selectRaw('payment_method, COUNT(*) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();

        // Top 5 Paket Terpopuler berdasarkan jumlah jamaah
        $topPackages = Package::withCount('jamaahs')
            ->orderByDesc('jamaahs_count')
            ->take(5)
            ->get();

        // --- 4. DATA DAFTAR QUICK ACTION / AKTIVITAS TERBARU ---
        $recentPendingPayments = Payment::where('status', 'pending')
            ->with(['jamaah.referensi', 'jamaah.package'])
            ->latest()
            ->take(5)
            ->get();

        $recentPendingJamaah = Jamaah::where('status', 'pending')
            ->with(['referensi', 'package'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingPackages = Package::where('departure_date', '>=', Carbon::today())
            ->withCount('jamaahs')
            ->orderBy('departure_date', 'asc')
            ->take(4)
            ->get();

        $topAgents = User::role('Agen')
            ->withCount('jamaahs')
            ->orderByDesc('jamaahs_count')
            ->take(5)
            ->get();

        return view('dashboard.admin.index', compact(
            'totalJamaah',
            'totalJamaahApproved',
            'totalJamaahPending',
            'totalJamaahUnpaid',
            'totalJamaahKejarLunas',
            'totalAgen',
            'totalAgenActive',
            'approvedPayments',
            'pendingPayments',
            'paidCommissions',
            'totalRevenue',
            'thisMonthRevenue',
            'totalReceivables',
            'totalPackages',
            'packagesWithFewerJamaah',
            'monthlyTransactions',
            'monthlyRevenue',
            'availableYears',
            'selectedYear',
            'paymentMethods',
            'topPackages',
            'recentPendingPayments',
            'recentPendingJamaah',
            'upcomingPackages',
            'topAgents'
        ));
    }
}
