@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center" style="color: #DDB02C; font-weight: bold;">Akun Saya</h2>

            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-light text-center" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <div class="rounded-circle" style="width: 100px; height: 100px; border: 5px solid #DDB02C; display: inline-block; line-height: 100px; font-size: 40px; color: #DDB02C;">
                                A
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <h5 class="font-weight-bold">{{ $agen->name }}</h5>
                            <p class="text-muted">Email: {{ $agen->email }}</p>
                            <p class="text-muted">Terdaftar Sejak: {{ $agen->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center" style="background-color: #F9F9F9;">
                    <h5>Total Komisi: Rp {{ number_format($totalKomisi, 0, ',', '.') }}</h5>
                    <a href="{{ route('commissions.agen.index') }}" class="btn btn-outline-primary">Lihat Riwayat Komisi</a>
                </div>
            </div>

            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0" style="color: #DDB02C;">Riwayat Komisi</h5>
                </div>
                <div class="card-body">
                    @if($riwayatKomisi->isEmpty())
                        <p class="text-center">Tidak ada riwayat komisi.</p>
                    @else
                        <table class="table table-bordered">
                            <thead style="background-color: #DFFFD6;">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Jamaah</th>
                                    <th scope="col">Jumlah Komisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatKomisi as $komisi)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $komisi->jamaah->nama }}</td>
                                        <td>Rp {{ number_format($komisi->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
