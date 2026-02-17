@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center" style="color: #DDB02C;">Profile</h2>
            <div class="row mt-4">

                <!-- Card User Info -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #DDB02C;">User Information</h5>
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone }}</p>
                            <p><strong>NIK:</strong> {{ $user->nik }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Jamaah Info -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #DDB02C;">Jamaah Under Your Supervision</h5>
                            @if($jamaahs->isEmpty())
                                <p>No jamaah found.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($jamaahs as $jamaah)
                                        <li class="list-group-item">
                                            <strong>{{ $jamaah->nama }}</strong> - Package: {{ $jamaah->package->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
