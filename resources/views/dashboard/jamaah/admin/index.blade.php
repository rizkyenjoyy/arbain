@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Daftar Jamaah</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="position: relative;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #DDB02C;">Daftar Jamaah Pending</h5>
                        <form action="{{ route('jamaah.admin.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search by name or NIK" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <!-- Garis bawah header -->
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
                </div>
                <div class="card-body">

                    <!-- Bagian Notifikasi -->
                    <div class="mb-3">
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Notifikasi Baru!</strong> Anda memiliki {{ auth()->user()->unreadNotifications->count() }} notifikasi baru.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background-color: #DFFFD6;">
                                <tr>
                                    <th>
                                        <a href="{{ route('jamaah.admin.index', ['sort' => 'nama', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header" data-direction="{{ request('direction') }}">
                                            Nama
                                        </a>
                                    </th>
                                    <th>Referensi</th>
                                    <th>No. Paspor</th>
                                    <th>NIK</th>
                                    <th>Paket</th>
                                    <th>Tempat Lahir</th>
                                    <th>
                                        <a href="{{ route('jamaah.admin.index', ['sort' => 'tanggal_lahir', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header" data-direction="{{ request('direction') }}">
                                            Tanggal Lahir
                                        </a>
                                    </th>
                                    <th>Jenis Kelamin</th>
                                    <th>Riwayat Penyakit</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th>
                                        <a href="{{ route('jamaah.admin.index', ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header" data-direction="{{ request('direction') }}">
                                            Status
                                        </a>
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jamaahs as $jamaah)
                                    <tr>
                                        <td>{{ $jamaah->nama }}</td>
                                        <td>{{ $jamaah->referensi->name ?? 'Tidak Ada' }}</td>
                                        <td>{{ $jamaah->no_paspor }}</td>
                                        <td>{{ $jamaah->nik }}</td>
                                        <td>{{ $jamaah->package->name ?? 'Tidak Ada' }}</td>
                                        <td>{{ $jamaah->tempat_lahir }}</td>
                                        <td>{{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d-m-Y') }}</td>
                                        <td>{{ $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $jamaah->riwayat_penyakit }}</td>
                                        <td>{{ $jamaah->alamat }}</td>
                                        <td>{{ $jamaah->kontak }}</td>
                                        <td>{{ $jamaah->status }}</td>
                                        <td>
                                            @if($jamaah->status === 'pending')
                                                <form action="{{ route('jamaah.admin.approve', $jamaah->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('jamaah.admin.reject', $jamaah->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- Penutup div.table-responsive -->

                    <!-- Pagination Links -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-center">
                            {{ $jamaahs->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
