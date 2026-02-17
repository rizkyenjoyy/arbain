<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf; // Import facade DomPDF

class HistoryPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $nik = $request->input('nik');
        $repaymentStatus = $request->input('repayment_status');
        $tanggalAwal = $request->input('tanggal_awal_pemberangkatan');
        $tanggalAkhir = $request->input('tanggal_akhir_pemberangkatan');

        // Query untuk Jamaah berdasarkan referensi_id agen
        $jamaahs = Jamaah::where('referensi_id', auth()->id())
            ->where('status', 'approved');

        // Tambahkan filter berdasarkan NIK, Status Pembayaran, dan Tanggal Pemberangkatan
        if ($nik) {
            $jamaahs->where('nik', $nik);
        }

        if ($repaymentStatus) {
            $jamaahs->whereHas('payments', function ($query) use ($repaymentStatus) {
                if ($repaymentStatus === 'paid') {
                    $query->where('status', 'approved');
                } else {
                    $query->where('status', 'pending');
                }
            });
        }

        if ($tanggalAwal && $tanggalAkhir) {
            $jamaahs->whereBetween('departure_date', [$tanggalAwal, $tanggalAkhir]);
        }

        // Ambil hasil dan paginate
        $jamaahs = $jamaahs->paginate(10);

        // Include the commission status
        foreach ($jamaahs as $jamaah) {
            $jamaah->commission_status = $jamaah->commission ? 'Dibayar' : 'Belum Dibayar';
        }

        // Tandai notifikasi JamaahApprovedNotification sebagai sudah dibaca
        $notifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\JamaahApprovedNotification');
        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return view('dashboard.riwayat.index', compact('jamaahs'));
    }


    public function exportPDF(Request $request)
    {
        // Ambil filter dari request untuk export
        $nik = $request->input('nik');
        $repaymentStatus = $request->input('repayment_status');
        $tanggalAwal = $request->input('tanggal_awal_pemberangkatan');
        $tanggalAkhir = $request->input('tanggal_akhir_pemberangkatan');

        // Ambil jamaah yang terkait dengan agen
        $jamaahs = Jamaah::where('referensi_id', auth()->id());

        // Tambahkan filter yang sama
        if ($nik) {
            $jamaahs->where('nik', $nik);
        }

        if ($repaymentStatus) {
            $jamaahs->whereHas('payments', function ($query) use ($repaymentStatus) {
                if ($repaymentStatus === 'paid') {
                    $query->where('status', 'approved');
                } else {
                    $query->where('status', 'pending');
                }
            });
        }

        if ($tanggalAwal && $tanggalAkhir) {
            $jamaahs->whereBetween('departure_date', [$tanggalAwal, $tanggalAkhir]);
        }

        // Ambil hasil
        $jamaahs = $jamaahs->with('package', 'payments')->get(); // Pastikan meng-include relasi

        // Include the commission status
        foreach ($jamaahs as $jamaah) {
            $jamaah->commission_status = $jamaah->commission ? 'Dibayar' : 'Belum Dibayar';
        }

        // Generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber();

        // Generate PDF
        $pdf = PDF::loadView('dashboard.riwayat.pdf', compact('jamaahs', 'invoiceNumber'));

        return $pdf->download('riwayat_pembayaran.pdf');
    }

    private function generateInvoiceNumber()
    {
        // Ambil timestamp saat ini
        $timestamp = now()->format('Ymd-His'); // Format: YYYYMMDD-HHMMSS
        return 'INV-' . $timestamp; // Contoh: INV-20230924-123456
    }

    public function exportExcel(Request $request)
    {
        // Filter logic (similar to your existing filter in the index)
        $nik = $request->input('nik');
        $repaymentStatus = $request->input('repayment_status');
        $tanggalAwal = $request->input('tanggal_awal_pemberangkatan');
        $tanggalAkhir = $request->input('tanggal_akhir_pemberangkatan');

        // Get Jamaah records based on filters
        $jamaahs = Jamaah::where('referensi_id', auth()->id());

        if ($nik) {
            $jamaahs->where('nik', $nik);
        }

        if ($repaymentStatus) {
            $jamaahs->whereHas('payments', function ($query) use ($repaymentStatus) {
                if ($repaymentStatus === 'paid') {
                    $query->where('status', 'approved');
                } else {
                    $query->where('status', 'pending');
                }
            });
        }

        if ($tanggalAwal && $tanggalAkhir) {
            $jamaahs->whereBetween('package.departure_date', [$tanggalAwal, $tanggalAkhir]);
        }

        // Fetch all records with commission and payment relationships
        $jamaahs = $jamaahs->with('package', 'payments', 'referensi', 'commission')->get();

        // Define the header for Excel
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment;filename="riwayat_pembayaran.xls"',
            'Cache-Control' => 'max-age=0',
        ];

        // Generate Excel content
        $content = view('dashboard.riwayat.excel', compact('jamaahs'))->render();

        // Return the response as an Excel file
        return Response::make($content, 200, $headers);
    }
    public function show($id)
    {
        // Temukan Jamaah berdasarkan ID
        $jamaah = Jamaah::findOrFail($id);

        // Tampilkan view dengan data Jamaah
        return view('dashboard.riwayat.show', compact('jamaah'));
    }

}









