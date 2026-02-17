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
                <h2 style="color: #DDB02C;">Edit Jamaah</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Jamaah Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('listjamaah.update', $jamaah->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" value="{{ old('nama', $jamaah->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Nomor Paspor -->
                        <div class="mb-3">
                            <label for="no_paspor" class="form-label">Nomor Paspor</label>
                            <input type="text" name="no_paspor" class="form-control @error('no_paspor') is-invalid @enderror" id="no_paspor" value="{{ old('no_paspor', $jamaah->no_paspor) }}" required>
                            @error('no_paspor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div class="mb-3">
                            <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" id="nik" value="{{ old('nik', $jamaah->nik) }}" required>
                            @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" value="{{ old('tempat_lahir', $jamaah->tempat_lahir) }}" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" value="{{ old('tanggal_lahir', $jamaah->tanggal_lahir) }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="L" {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $jamaah->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $jamaah->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Kontak -->
                        <div class="mb-3">
                            <label for="kontak" class="form-label">Kontak</label>
                            <input type="text" name="kontak" class="form-control @error('kontak') is-invalid @enderror" id="kontak" value="{{ old('kontak', $jamaah->kontak) }}" required>
                            @error('kontak')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Riwayat Penyakit -->
                        <div class="mb-3">
                            <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                            <textarea name="riwayat_penyakit" id="riwayat_penyakit" class="form-control @error('riwayat_penyakit') is-invalid @enderror" rows="3">{{ old('riwayat_penyakit', $jamaah->riwayat_penyakit) }}</textarea>
                            @error('riwayat_penyakit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Referensi Agen -->
                        <div class="mb-3">
                            <label for="referensi_id" class="form-label">Referensi Agen</label>
                            <select name="referensi_id" id="referensi_id" class="form-select @error('referensi_id') is-invalid @enderror" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $jamaah->referensi_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('referensi_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Paket -->
                        <div class="mb-3">
                            <label for="package_id" class="form-label">Paket</label>
                            <select name="package_id" id="package_id" class="form-select @error('package_id') is-invalid @enderror" required>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id', $jamaah->package_id) == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn" style="background-color: #DDB02C; color: white;">Update</button>
                            <a href="{{ route('listjamaah.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
