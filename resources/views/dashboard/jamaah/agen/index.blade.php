@extends('dashboard.layouts.main')

@section('content')

<div class="container-fluid p-4">
    <div class="row justify-content-between">
        <div class="text-center">
            <h2 style="color: #DDB02C;">Daftar Jamaah</h2>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
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
        <div class="card-header bg-white d-flex justify-content-between align-items-center" style="position: relative;">
            <h5 class="mb-0" style="color: #DDB02C;">Daftar Jamaah</h5>
            <a href="{{ route('jamaah.agen.create') }}" class="btn btn-success" style="color: white;"><i class='bx bxs-add-to-queue'></i> Jamaah Baru</a>

            <!-- Garis bawah header -->
            <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
        </div>
        <div class="card-body">
            <form action="{{ route('jamaah.agen.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by Nama or NIK" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead style="background-color: #DFFFD6;">
                        <tr>
                            <th>Nama</th>
                            <th>No. Paspor</th>
                            <th>NIK</th>
                            <th>Paket</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Riwayat Penyakit</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jamaahs as $jamaah)
                            <tr>
                                <td>{{ $jamaah->nama }}</td>
                                <td>{{ $jamaah->no_paspor }}</td>
                                <td>{{ $jamaah->nik }}</td>
                                <td>{{ $jamaah->package->name ?? 'Tidak Ada' }}</td>
                                <td>{{ $jamaah->tempat_lahir }}</td>
                                <td>{{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d-m-Y') }}</td>
                                <td>{{ $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $jamaah->riwayat_penyakit }}</td>
                                <td>{{ $jamaah->alamat }}</td>
                                <td>{{ $jamaah->kontak }}</td>
                                <td>{{ ucfirst($jamaah->status) }}</td>
                                <td>
                                    <form action="{{ route('jamaah.agen.destroy', $jamaah->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?')"><i class='bx bxs-trash-alt'></i> Hapus</button>
                                    </form>
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
    </div>
</div>
@endsection
