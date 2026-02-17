<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\PaymentApprovedNotification;
use Illuminate\Support\Facades\Notification;

class AdminPaymentController extends Controller
{
    // Menampilkan daftar semua pembayaran yang diajukan
    public function index(Request $request)
    {

        $query = Payment::with('jamaah', 'jamaah.package')->where('status', 'pending');

        // Pencarian berdasarkan nama Jamaah atau nama paket
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('jamaah', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('jamaah.package', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        // Mark all payment notifications as read
        $paymentNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\PaymentAddedNotification');
        foreach ($paymentNotifications as $notification) {
            $notification->markAsRead();
        }

        // Sortir berdasarkan kolom tertentu
        if ($request->has('sort') && in_array($request->sort, ['id', 'nama', 'jumlah_pembayaran', 'status'])) {
            $query->orderBy($request->sort, $request->get('direction', 'asc'));
        }

        // Paginasi hasil
        $payments = $query->paginate(10); // Tampilkan 10 hasil per halaman

        return view('dashboard.payments.admin.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(403, 'Pembayaran ini sudah diproses.');
        }

        // Update status menjadi 'approved' dan catat id admin yang melakukan proses
        $payment->update([
            'status' => 'approved',
            'processed_by' => Auth::id(), // Isi kolom `processed_by` dengan id admin yang sedang login
        ]);

        // Tambahkan jumlah pembayaran ke total_amount jamaah terkait
        $payment->jamaah->increment('total_amount', $payment->amount);

        // Kirim notifikasi ke agen yang mengajukan pembayaran
        $agen = $payment->jamaah->referensi;
        Notification::send($agen, new PaymentApprovedNotification($payment));

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil disetujui.');
    }

    // Admin menolak pembayaran
    public function reject(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(403, 'Pembayaran ini sudah diproses.');
        }

        // Update status menjadi 'rejected' dan catat id admin yang melakukan proses
        $payment->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(), // Isi kolom `processed_by` dengan id admin yang sedang login
        ]);

        $payment->delete(); // Hapus pembayaran

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil ditolak dan dihapus.');
    }
}
