<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class ListJamaahController extends Controller
{
    public function index(Request $request)
    {
        $searchNIK = $request->input('nik');
        $status = $request->input('status');
        $tanggalKeberangkatan = $request->input('tanggal_keberangkatan');

        $jamaahs = Jamaah::with(['package', 'payments', 'referensi', 'processedBy']) // Tambahkan 'processedBy'
            ->when($searchNIK, function($query, $searchNIK) {
                return $query->where('nik', 'like', "%{$searchNIK}%");
            })
            ->when($status, function($query, $status) {
                return $query->whereHas('package', function ($q) use ($status) {
                    if ($status === 'lunas') {
                        return $q->whereRaw('jamaahs.total_amount >= packages.price');
                    } else {
                        return $q->whereRaw('jamaahs.total_amount < packages.price');
                    }
                });
            })
            ->when($tanggalKeberangkatan, function($query, $tanggalKeberangkatan) {
                $departureDate = Carbon::parse($tanggalKeberangkatan);
                return $query->whereHas('package', function($q) use ($departureDate) {
                    $q->whereDate('departure_date', $departureDate);
                });
            })
            ->where('status', 'approved')
            ->paginate(10);

        return view('dashboard.listjamaah.index', compact('jamaahs'));
    }



    // Menghapus data jamaah
    public function destroy($id)
    {
        $jamaah = Jamaah::findOrFail($id);
        $jamaah->delete();

        return redirect()->route('listjamaah.index')->with('success', 'Data jamaah berhasil dihapus');
    }

    // Menampilkan form edit jamaah
    public function edit($id)
    {
        $jamaah = Jamaah::findOrFail($id);
        $packages = Package::all();
        $users = User::all();

        return view('dashboard.listjamaah.edit', compact('jamaah', 'packages', 'users'));
    }

    // Update data jamaah
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_paspor' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'riwayat_penyakit' => 'nullable|string',
            'referensi_id' => 'required|exists:users,id', // Tambahkan validasi referensi_id
            'package_id' => 'required|exists:packages,id',
        ]);

        $jamaah = Jamaah::findOrFail($id);
        $jamaah->update([
            'nama' => $request->nama,
            'no_paspor' => $request->no_paspor,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'riwayat_penyakit' => $request->riwayat_penyakit, // Tambahkan riwayat_penyakit
            'referensi_id' => $request->referensi_id, // Tambahkan referensi_id
            'package_id' => $request->package_id,
        ]);

        return redirect()->route('listjamaah.index')->with('success', 'Data jamaah berhasil diperbarui.');
    }



    public function exportCsv(Request $request)
    {
        // Ambil data berdasarkan filter yang sama seperti di metode index
        $searchNIK = $request->input('nik');
        $status = $request->input('status');
        $repaymentStatus = $request->input('repayment_status');
        $tanggalAwalPemberangkatan = $request->input('tanggal_awal_pemberangkatan');
        $tanggalAkhirPemberangkatan = $request->input('tanggal_akhir_pemberangkatan');

        $jamaahs = Jamaah::with(['package', 'payments', 'referensi'])
            ->when($searchNIK, function($query, $searchNIK) {
                return $query->where('nik', 'like', "%{$searchNIK}%");
            })
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($repaymentStatus, function($query, $repaymentStatus) {
                return $query->whereHas('payments', function ($query) use ($repaymentStatus) {
                    if ($repaymentStatus === 'paid') {
                        $query->where('status', 'approved');
                    } else {
                        $query->where('status', '!=', 'approved');
                    }
                });
            })
            ->when($tanggalAwalPemberangkatan && $tanggalAkhirPemberangkatan, function($query) use ($tanggalAwalPemberangkatan, $tanggalAkhirPemberangkatan) {
                $start = Carbon::parse($tanggalAwalPemberangkatan);
                $end = Carbon::parse($tanggalAkhirPemberangkatan);
                return $query->whereHas('package', function($q) use ($start, $end) {
                    $q->whereBetween('departure_date', [$start, $end]);
                });
            })
            ->where('status', 'approved')
            ->get();

        // Buat CSV
        $csvFileName = 'jamaah_export_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://output', 'w');

        // Set header untuk file CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

        // Tulis header kolom
        fputcsv($handle, [
            'No',
            'Nama',
            'NIK',
            'No. Paspor',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Kontak',
            'Paket',
            'Tanggal Keberangkatan',
            'Jatuh Tempo',
            'Total Pembayaran',
            'Referensi Agen',
            'Status Komisi'
        ]);

        // Tulis data jamaah ke file CSV
        foreach ($jamaahs as $index => $jamaah) {
            fputcsv($handle, [
                $index + 1,
                $jamaah->nama,
                $jamaah->nik,
                $jamaah->no_paspor,
                $jamaah->tempat_lahir,
                \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d-m-Y'),
                $jamaah->jenis_kelamin,
                $jamaah->alamat,
                $jamaah->kontak,
                $jamaah->package->name,
                \Carbon\Carbon::parse($jamaah->package->departure_date)->format('d-m-Y'),
                $jamaah->jatuh_tempo,
                'Rp ' . number_format($jamaah->payments->sum('amount'), 0, ',', '.'),
                $jamaah->referensi->name ?? 'Tidak Ada',
                $jamaah->tagihan_status,
            ]);
        }

        fclose($handle);
        exit;
    }

    public function getPaymentHistory($id)
    {
        $jamaah = Jamaah::with(['payments' => function ($query) {
            $query->with('processedBy'); // pastikan ada relasi processedBy pada model Payment
        }])->findOrFail($id);

        $paymentHistory = $jamaah->payments->map(function ($payment) {
            return [
                'payment_date' => $payment->created_at->format('d-m-Y'),
                'amount' => $payment->amount,
                'status' => $payment->status,
                'processed_by' => $payment->processedBy->name ?? 'Tidak diketahui'
            ];
        });

        return response()->json($paymentHistory);
    }


}
