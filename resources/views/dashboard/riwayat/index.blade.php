@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <h2 style="color: #DDB02C;">Jamaah Saya Yang Sudah Disetujui</h2>
    <!-- List Notifikasi Jamaah yang disetujui -->
    <div class="mb-4">
        <h4>Notifikasi Jamaah Disetujui:</h4>
        <ul>
            @foreach(auth()->user()->notifications->where('type', 'App\Notifications\JamaahApprovedNotification')->where('read_at', null) as $notification)
                <li>
                    Jamaah {{ $notification->data['jamaah_nama'] }} (NIK: {{ $notification->data['jamaah_nik'] }}) telah disetujui.
                    <a href="{{ route('riwayat.pembayaran.show', $notification->data['jamaah_id']) }}">Lihat Detail</a>
                </li>
                @php
                    // Tandai notifikasi sebagai dibaca
                    $notification->markAsRead();
                @endphp
            @endforeach
        </ul>
    </div>
    <div class="mb-4">
        <h4>Notifikasi Pembayaran Disetujui:</h4>
        <ul>
            @foreach(auth()->user()->unreadNotifications->where('type', 'App\Notifications\PaymentApprovedNotification')->where('read_at', null) as $notification)
                <li>
                    Pembayaran untuk Jamaah {{ $notification->data['jamaah_nama'] }} (NIK: {{ $notification->data['jamaah_nik'] }}) sebesar {{ $notification->data['amount'] }} telah disetujui.
                    <a href="{{ route('riwayat.pembayaran.show', $notification->data['payment_id']) }}">Lihat Detail</a>
                </li>
                @php
                    // Tandai notifikasi sebagai dibaca
                    $notification->markAsRead();
                @endphp
            @endforeach
        </ul>
    </div>



    <!-- Form Filter -->
    <form action="{{ route('riwayat.pembayaran.index') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <!-- Filter NIK -->
            <div class="col-md-4 col-lg-3">
                <label class="form-label">Nomor Induk Kependudukan</label>
                <input type="text" name="nik" class="form-control form-control-custom" placeholder="Cari 16 Digit NIK"
                    value="{{ request()->query('nik') }}">
            </div>

            <!-- Filter Status Pelunasan -->
            <div class="col-md-4 col-lg-3">
                <label for="repayment_status" class="form-label">Status Pelunasan</label>
                <select id="repayment_status" name="repayment_status" class="form-select">
                    <option value="">Semua</option>
                    <option value="unpaid" {{ request('repayment_status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ request('repayment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-filter'></i> Filter
                </button>
                <a href="{{ route('riwayat.pembayaran.index') }}" class="btn btn-secondary ms-2">
                    <i class='bx bx-refresh'></i> Reset
                </a>
                <!-- Tombol Ekspor PDF -->
                <a href="{{ route('riwayat.pembayaran.export', request()->query()) }}" class="btn btn-success ms-2">
                    <i class='bx bx-download'></i> Invoice PDF
                </a>
                <a href="{{ route('riwayat.pembayaran.export.excel', request()->query()) }}" class="btn btn-success ms-2">
                    <i class='bx bx-download'></i> Export Excel
                </a>
            </div>
        </div>
    </form>

    <!-- Tabel Daftar Jamaah -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead style="background-color: #DFFFD6;">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>No. Paspor</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Paket</th>
                    <th>Tanggal Keberangkatan</th>
                    <th>Jatuh Tempo</th>
                    <th>Total Pembayaran</th>
                    <th>Tunggakan</th> <!-- Tambahkan kolom Tunggakan -->
                    <th>Referensi Agen</th>
                    <th>Status Komisi</th>
                    <th>Diproses oleh</th> <!-- Tambahkan kolom Status Komisi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($jamaahs as $index => $jamaah)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $jamaah->nama }}</td>
                    <td>{{ $jamaah->nik }}</td>
                    <td>{{ $jamaah->no_paspor }}</td>
                    <td>{{ $jamaah->tempat_lahir }}</td>
                    <td>{{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d-m-Y') }}</td>
                    <td>{{ $jamaah->jenis_kelamin }}</td>
                    <td>{{ $jamaah->alamat }}</td>
                    <td>{{ $jamaah->kontak }}</td>
                    <td>{{ $jamaah->package->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($jamaah->package->departure_date)->format('d-m-Y') }}</td>
                    <td>{{ $jamaah->jatuh_tempo }}</td> <!-- Jatuh Tempo -->
                    <td>Rp {{ number_format($jamaah->total_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($jamaah->sisa_pembayaran, 0, ',', '.') }}</td> <!-- Tunggakan -->
                    <td>{{ $jamaah->referensi->name ?? 'Tidak Ada' }}</td>
                    <td>{{ $jamaah->commission_status }}</td>
                    <td>{{ $jamaah->processedBy->name ?? 'Tidak Diketahui' }}</td> <!-- Status Komisi -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-3">
        <div class="d-flex justify-content-center">
            {{ $jamaahs->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
