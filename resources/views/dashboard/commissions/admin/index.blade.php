@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="color: #DDB02C;">Daftar Komisi Agen</h2>
                <a href="{{ route('commissions.create') }}" class="btn btn-success" style="color: white;">
                    <i class="bi bi-plus-circle"></i> Tambah Komisi
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($commissions->isEmpty())
                <div class="alert alert-warning">Tidak ada Komisi yang tersedia.</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="position: relative;">
                    <h5 class="mb-0" style="color: #DDB02C;">Semua Komisi Agen</h5>
                    <!-- Garis bawah header -->
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
                </div>
                <div class="card-body">
                    <!-- Form Filter -->
                    <form action="{{ route('commissions.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <!-- Filter Nama Jamaah -->
                            <div class="col-md-4">
                                <label for="jamaah_name" class="form-label">Nama Jamaah</label>
                                <input type="text" id="jamaah_name" name="jamaah_name" class="form-control" placeholder="Cari Nama Jamaah"
                                    value="{{ request()->query('jamaah_name') }}">
                            </div>

                            <!-- Filter Nama Agen -->
                            <div class="col-md-4">
                                <label for="agen_name" class="form-label">Nama Agen</label>
                                <input type="text" id="agen_name" name="agen_name" class="form-control" placeholder="Cari Nama Agen"
                                    value="{{ request()->query('agen_name') }}">
                            </div>

                            <!-- Tombol Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-filter'></i> Filter
                                </button>
                                <a href="{{ route('commissions.index') }}" class="btn btn-secondary ms-2">
                                    <i class='bx bx-refresh'></i> Reset
                                </a>
                                <a href="{{ route('commissions.admin.export') }}" class="btn btn-success">
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
                                    <th scope="col">Nama Agen</th>
                                    <th scope="col">Jumlah Komisi</th>
                                    <th scope="col">Harga Paket</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $commission)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $commission->jamaah->nama }}</td>
                                        <td>{{ $commission->jamaah->nik }}</td>
                                        <td>{{ $commission->agen->name }}</td>
                                        <td>Rp {{ number_format($commission->amount, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($commission->jamaah->package->price, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('commissions.destroy', $commission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus komisi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
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
