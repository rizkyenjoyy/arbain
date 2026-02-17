@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2 style="color: #DDB02C;">Daftar Jamaah (Approved)</h2>
    <div class="card-header bg-white mb-3" style="position: relative;">
        <!-- Garis bawah header -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
    </div>

    <!-- Form Filter -->
    <form action="{{ route('listjamaah.index') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <!-- Filter NIK -->
            <div class="col-md-4 col-lg-3">
                <label class="form-label">Nomor Induk Kependudukan</label>
                <input type="text" name="nik" class="form-control form-control-custom" placeholder="Cari 16 Digit NIK" value="{{ request()->query('nik') }}">
            </div>
            <!-- Filter Status -->
            <div class="col-md-4 col-lg-3">
                <label for="status" class="form-label">Status Tagihan</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum lunas" {{ request('status') == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>

            <div class="col-md-4 col-lg-3">
                <label for="tanggal_keberangkatan" class="form-label">Tanggal Keberangkatan</label>
                <input type="date" id="tanggal_keberangkatan" name="tanggal_keberangkatan" class="form-control mb-3" value="{{ request('tanggal_keberangkatan') }}">
            </div>

            <!-- Tombol Submit -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-filter'></i> Filter
                </button>
                <a href="{{ route('listjamaah.index') }}" class="btn btn-secondary ms-2">
                    <i class='bx bx-refresh'></i> Reset
                </a>
                <a href="{{ route('listjamaah.exportCsv', request()->query()) }}" class="btn btn-success">
                    <i class='bx bx-download'></i> Ekspor CSV
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
                    <th>Tunggakan</th>
                    <th>Referensi Agen</th>
                    <th>Status Pembayaran</th>
                    <th>Diproses Oleh</th> <!-- Kolom Baru -->
                    <th>Aksi</th>
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
                    <td>{{ $jamaah->jatuh_tempo }}</td>
                    <td>Rp {{ number_format($jamaah->payments->sum('amount'), 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($jamaah->sisa_pembayaran, 0, ',', '.') }}</td>
                    <td>{{ $jamaah->referensi->name ?? 'Tidak Ada' }}</td>
                    <td>{{ $jamaah->tagihan_status }}</td>
                    <td>{{ $jamaah->processedBy->name ?? 'Tidak Diketahui' }}</td> <!-- Tampilkan Nama User -->
                    <td>
                        <a href="{{ route('listjamaah.edit', $jamaah->id) }}" class="btn btn-warning btn-sm">
                            <i class='bx bx-edit'></i> Edit
                        </a>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentHistoryModal" data-jamaah-id="{{ $jamaah->id }}">
                            <i class='bx bx-history'></i> Riwayat Pembayaran
                        </button>
                    </td>
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

<!-- Modal untuk Riwayat Pembayaran -->
<div class="modal fade" id="paymentHistoryModal" tabindex="-1" aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentHistoryModalLabel">Riwayat Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Diproses Oleh</th>
                        </tr>
                    </thead>
                    <tbody id="paymentHistoryBody">
                        <!-- Data riwayat pembayaran akan dimuat di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentHistoryModal = document.getElementById('paymentHistoryModal');
        paymentHistoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const jamaahId = button.getAttribute('data-jamaah-id');

            fetch(`/dashboard/listjamaah/${jamaahId}/payments`)
                .then(response => response.json())
                .then(data => {
                    const paymentHistoryBody = document.getElementById('paymentHistoryBody');
                    paymentHistoryBody.innerHTML = '';

                    data.forEach(payment => {
                        const row = `<tr>
                            <td>${payment.payment_date}</td>
                            <td>Rp ${payment.amount.toLocaleString('id-ID')}</td>
                            <td>${payment.status}</td>
                            <td>${payment.processed_by}</td>
                        </tr>`;
                        paymentHistoryBody.innerHTML += row;
                    });
                });
        });
    });
</script>

@endsection
