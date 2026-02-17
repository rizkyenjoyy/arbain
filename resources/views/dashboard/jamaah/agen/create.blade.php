@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Create New Jamaah</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Jamaah Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('jamaah.agen.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" id="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="no_paspor" class="form-label">No. Paspor</label>
                            <input type="text" name="no_paspor" class="form-control" id="no_paspor" required>
                        </div>

                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" id="nik" required>
                        </div>

                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" id="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                            <textarea name="riwayat_penyakit" id="riwayat_penyakit" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" id="alamat" required>
                        </div>

                        <div class="mb-3">
                            <label for="kontak" class="form-label">Kontak</label>
                            <input type="text" name="kontak" class="form-control" id="kontak" required>
                        </div>

                        <div class="mb-3">
                            <label for="package_id" class="form-label">Paket</label>
                            <select name="package_id" class="form-select" id="package_id" required>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success" style="color: white;">Create</button>
                            <a href="{{ route('jamaah.agen.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
