@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <h2>Detail Jamaah: {{ $jamaah->nama }}</h2>

    <p><strong>NIK:</strong> {{ $jamaah->nik }}</p>
    <p><strong>No. Paspor:</strong> {{ $jamaah->no_paspor }}</p>
    <p><strong>Tempat Lahir:</strong> {{ $jamaah->tempat_lahir }}</p>
    <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d-m-Y') }}</p>
    <p><strong>Jenis Kelamin:</strong> {{ $jamaah->jenis_kelamin }}</p>
    <p><strong>Alamat:</strong> {{ $jamaah->alamat }}</p>
    <p><strong>Kontak:</strong> {{ $jamaah->kontak }}</p>
    <p><strong>Status:</strong> {{ $jamaah->status }}</p>
    <p><strong>Total Pembayaran:</strong> Rp {{ number_format($jamaah->total_amount, 0, ',', '.') }}</p>

    <!-- Tambahkan informasi lain sesuai kebutuhan -->
</div>
@endsection
