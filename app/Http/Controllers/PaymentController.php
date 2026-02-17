<?php

namespace App\Http\Controllers;

use App\Notifications\PaymentAddedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Models\Jamaah;
use App\Models\Payment;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Menampilkan daftar pembayaran yang dilakukan agen untuk jamaah
    public function index(Request $request)
    {
        $query = Payment::whereHas('jamaah', function ($query) {
            $query->where('referensi_id', auth()->id())
                  ->where('status', 'approved'); // Menambahkan kondisi untuk status jamaah
        })->where('status', 'pending');

        // Search by Jamaah name or Payment ID
        if ($request->has('search') && !empty($request->search)) {
            $query->where('id', $request->search)
                  ->orWhereHas('jamaah', function($query) use ($request) {
                      $query->where('nama', 'like', '%' . $request->search . '%');
                  });
        }

        // Paginate the results
        $payments = $query->paginate(10); // Menampilkan 10 pembayaran per halaman

        return view('dashboard.payments.agen.index', compact('payments'));
    }




    // Form untuk membuat pembayaran baru
    public function create()
    {
        // Menampilkan semua jamaah yang diajukan oleh agen
        $jamaahs = Jamaah::where('referensi_id', auth()->id())
                         ->where('status', 'approved') // Hanya mengambil jamaah yang approved
                         ->get()
                         ->filter(function($jamaah) {
                             return $jamaah->tagihan_status == 'belum lunas'; // Filter yang belum lunas
                         });

        return view('dashboard.payments.agen.create', compact('jamaahs'));
    }


    // Menyimpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,transfer',
            'payment_proof' => 'nullable|mimes:jpg,jpeg,png,pdf|required_if:payment_method,transfer',
        ]);

        $payment = Payment::create([
            'jamaah_id' => $request->jamaah_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_proof' => $request->file('payment_proof') ? $request->file('payment_proof')->store('payments', 'public') : null,
            'status' => 'pending', // Pembayaran baru selalu pending
        ]);
        // Kirim notifikasi ke semua admin
        $adminUsers = User::role('Admin')->get(); // Ambil semua user dengan role admin
        Notification::send($adminUsers, new PaymentAddedNotification($payment));

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diajukan.');
    }

    // Form untuk mengedit pembayaran (hanya jika statusnya pending)
    public function edit(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(403, 'Tidak bisa mengedit pembayaran yang sudah di-approve atau ditolak.');
        }

        $jamaahs = Jamaah::where('referensi_id', auth()->id())->get();
        return view('dashboard.payments.agen.edit', compact('payment', 'jamaahs'));
    }

    // Mengupdate pembayaran
    public function update(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(403, 'Tidak bisa mengedit pembayaran yang sudah di-approve atau ditolak.');
        }

        $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,transfer',
            'payment_proof' => 'nullable|mimes:jpg,jpeg,png,pdf|required_if:payment_method,transfer',
        ]);

        $payment->update([
            'jamaah_id' => $request->jamaah_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_proof' => $request->file('payment_proof') ? $request->file('payment_proof')->store('payments', 'public') : $payment->payment_proof,
        ]);


        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    // Menghapus pembayaran
    public function destroy(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            abort(403, 'Tidak bisa menghapus pembayaran yang sudah di-approve atau ditolak.');
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function searchJamaah(Request $request)
    {
        $search = $request->input('search');

        // Cari jamaah berdasarkan nama atau NIK dengan status "approved" dan "belum lunas"
        $jamaahs = Jamaah::where('referensi_id', auth()->id())
                          ->where('status', 'approved') // Filter status approved
                          ->with(['package', 'payments']) // Relasi dengan paket dan pembayaran
                          ->get()
                          ->filter(function($jamaah) use ($search) {
                              // Filter tambahan: Hanya menampilkan jamaah dengan tagihan_status "belum lunas"
                              return $jamaah->tagihan_status == 'belum lunas' && (
                                  stripos($jamaah->nama, $search) !== false || // Cari berdasarkan nama
                                  stripos($jamaah->nik, $search) !== false    // Cari berdasarkan NIK
                              );
                          })
                          ->map(function($jamaah) {
                              // Menghitung total pembayaran dan sisa tunggakan
                              $totalPaid = $jamaah->total_amount;
                              $packagePrice = $jamaah->package->price ?? 0;
                              $outstanding = $packagePrice - $totalPaid;

                              // Format outstanding dengan tanda titik
                              $formattedOutstanding = number_format($outstanding, 0, ',', '.');

                              // Mengambil tanggal jatuh tempo dari atribut
                              $dueDate = $jamaah->jatuh_tempo;
                              $daysLeft = $dueDate ? now()->diffInDays($dueDate, false) : null;

                              // Jika selisih hari negatif, artinya sudah lewat jatuh tempo
                              $dueText = $daysLeft !== null
                                  ? ($daysLeft > 0 ? $daysLeft . ' days left' : 'Overdue by ' . abs($daysLeft) . ' days')
                                  : 'No due date';

                              return [
                                  'id' => $jamaah->id,
                                  'nama' => $jamaah->nama,
                                  'nik' => $jamaah->nik,
                                  'package_name' => $jamaah->package->name ?? 'N/A',
                                  'outstanding' => $formattedOutstanding, // Menggunakan format yang telah diubah
                                  'days_left' => $dueText
                              ];
                          });

        // Kembalikan hasil pencarian sebagai JSON
        return response()->json($jamaahs->values());
    }



}
