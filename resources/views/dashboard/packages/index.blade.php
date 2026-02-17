@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="color: #DDB02C;">Daftar Paket</h2>
                <a href="{{ route('packages.create') }}" class="btn btn-success" style="color: white;">
                    <i class="bi bi-plus-circle"></i> Tambah Paket
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="position: relative;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #DDB02C;">All Packages</h5>
                        <form action="{{ route('packages.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search by name" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>

                    <!-- Garis bawah header -->
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-header" style="background-color: #DFFFD6;">
                                <tr>
                                    <th scope="col">
                                        <a href="{{ route('packages.index', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header" data-direction="{{ request('direction') }}">
                                            Nama Paket
                                        </a>
                                    </th>
                                    <th scope="col">Description</th>
                                    <th scope="col">
                                        <a href="{{ route('packages.index', ['sort' => 'departure_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header" data-direction="{{ request('direction') }}">
                                            Departure Date
                                        </a>
                                    </th>
                                    <th scope="col">
                                        <a href="{{ route('packages.index', ['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header" data-direction="{{ request('direction') }}">
                                            Price
                                        </a>
                                    </th>
                                    <th scope="col">Total Jamaah</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $package->name }}</td>
                                        <td>{{ $package->description }}</td>
                                        <td>{{ \Carbon\Carbon::parse($package->departure_date)->format('d-m-Y') }}</td>
                                        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                                        <td>{{ $package->jamaahs_count }}</td>
                                        <td>
                                            <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Delete
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
                        {{ $packages->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this package?');
    }
</script>

@endsection
