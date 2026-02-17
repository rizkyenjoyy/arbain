<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use DataTables;

class AgenPaymentHistoryController extends Controller
{
    public function index()
    {
        return view('dashboard.payments.agen.history');
    }

    public function getPayments(Request $request)
    {
        if ($request->ajax()) {
            // Ambil data pembayaran yang hanya berkaitan dengan agen yang sedang login
            $payments = Payment::with('jamaah', 'jamaah.package')
                ->whereHas('jamaah', function ($query) {
                    $query->where('referensi_id', auth()->id());
                })
                ->where('status', 'approved') // Tambahkan filter untuk pembayaran yang sudah di-approve
                ->select('payments.*');

            // Gunakan DataTables untuk server-side processing
            return Datatables::of($payments)
                ->addColumn('jamaah_name', function ($payment) {
                    return $payment->jamaah->nama;
                })
                ->addColumn('package_name', function ($payment) {
                    return $payment->jamaah->package->name;
                })
                ->addColumn('price', function ($payment) {
                    return $payment->jamaah->package->price;
                })
                ->addColumn('due', function ($payment) {
                    // Hitung tunggakan (price - total pembayaran)
                    return $payment->jamaah->package->price - $payment->jamaah->amount;
                })
                ->addColumn('payment_status', function ($payment) {
                    return ucfirst($payment->status);
                })
                ->addColumn('departure_date', function ($payment) {
                    return $payment->jamaah->package->departure_date;
                })
                ->make(true);
        }
    }
}
