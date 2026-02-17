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
                            <h5 class="font-weight-bold">{{ $admin->name }}</h5>
                            <p class="text-muted">Email: {{ $admin->email }}</p>
                            <p class="text-muted">Terdaftar Sejak: {{ $admin->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center" style="background-color: #F9F9F9;">
                    <a href="{{ route('commissions.index') }}" class="btn btn-outline-primary">Kelola Komisi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
