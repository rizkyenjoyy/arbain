<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommissionAgenController extends Controller
{
    public function index(Request $request)
    {
        // Ambil agen_id dari auth
        $agenId = auth()->user()->id; // Sesuaikan jika Anda menggunakan cara lain untuk mengidentifikasi agen

        // Mark commission notifications as read
        $commissionNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\CommissionAddedNotification');
        foreach ($commissionNotifications as $notification) {
            $notification->markAsRead();
        }
        // Query untuk mengambil komisi berdasarkan agen
        $commissions = Commission::where('agen_id', $agenId)
            ->with('jamaah.package') // Load paket jamaah sekaligus untuk efisiensi
            ->when($request->jamaah_name, function ($query) use ($request) {
                return $query->whereHas('jamaah', function ($q) use ($request) {
                    $q->where('nama', 'LIKE', '%' . $request->jamaah_name . '%');
                });
            })
            ->paginate(10);

        return view('dashboard.commissions.agen.index', compact('commissions'));
    }
    public function exportExcel(Request $request)
    {
        $agenId = auth()->user()->id;

        // Fetch commissions with filtering (if needed)
        $commissions = Commission::where('agen_id', $agenId)
            ->with('jamaah.package')
            ->when($request->jamaah_name, function ($query) use ($request) {
                return $query->whereHas('jamaah', function ($q) use ($request) {
                    $q->where('nama', 'LIKE', '%' . $request->jamaah_name . '%');
                });
            })
            ->get();

        // Define the header for Excel
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment;filename="daftar_komisi.xls"',
            'Cache-Control' => 'max-age=0',
        ];

        // Generate Excel content
        $content = view('dashboard.commissions.agen.excel', compact('commissions'))->render();

        // Return the response as an Excel file
        return Response::make($content, 200, $headers);
    }
}


