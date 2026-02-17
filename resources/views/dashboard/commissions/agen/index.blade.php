@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="color: #DDB02C;">Daftar Komisi Anda</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($commissions->isEmpty())
                <div class="alert alert-warning">Tidak ada Komisi yang tersedia.</div>
            @endif
            <!-- Bagian Notifikasi -->
            <div class="mb-3">
                @php
                    $commissionNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\CommissionAddedNotification');
                @endphp

                @if ($commissionNotifications->count() > 0)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Notifikasi Baru!</strong> Anda memiliki {{ $commissionNotifications->count() }} notifikasi komisi baru.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>


            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Komisi Agen</h5>
                </div>
                <div class="card-body">
                    <!-- Form Filter -->
                    <form action="{{ route('commissions.agen.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <!-- Filter Nama Jamaah -->
                            <div class="col-md-4">
                                <label for="jamaah_name" class="form-label">Nama Jamaah</label>
                                <input type="text" id="jamaah_name" name="jamaah_name" class="form-control" placeholder="Cari Nama Jamaah"
                                    value="{{ request()->query('jamaah_name') }}">
                            </div>

                            <!-- Tombol Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-filter'></i> Filter
                                </button>
                                <a href="{{ route('commissions.agen.index') }}" class="btn btn-secondary ms-2">
                                    <i class='bx bx-refresh'></i> Reset
                                </a>
                                <a href="{{ route('commissions.agen.export') }}" class="btn btn-success">
                                    <i class='bx bx-export'></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Daftar Komisi -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background-color: #DFFFD6;">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Jamaah</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Nama Paket</th>
                                    <th scope="col">Jumlah Komisi</th>
                                    <th scope="col">Harga Paket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $commission)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration + ($commissions->currentPage() - 1) * $commissions->perPage() }}</th>
                                        <td>{{ $commission->jamaah->nama }}</td>
                                        <td>{{ $commission->jamaah->nik }}</td>
                                        <td>{{ $commission->jamaah->package->name }}</td>
                                        <td>Rp {{ number_format($commission->amount, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($commission->jamaah->package->price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $commissions->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
