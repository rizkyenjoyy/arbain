@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="text-center p-2">
                <h2>Users List</h2>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="position: relative;">
                    <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                        <!-- Search -->
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by name" value="{{ request('search') }}">

                        <!-- Filter by Role -->
                        <select name="role" class="form-control me-2">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Add New User
                    </a>

                    <!-- Garis bawah header -->
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-header" style="background-color: #DFFFD6;">
                                <tr>
                                    <th>
                                        <a href="{{ route('users.index', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header">
                                            Name
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('users.index', ['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header">
                                            Email
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('users.index', ['sort' => 'phone', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="sortable-header">
                                            Phone
                                        </a>
                                    </th>
                                    <th>Role</th>
                                    <th>Status</th> <!-- Status aktif/tidak aktif ditampilkan -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <!-- Menampilkan status online/offline -->
                                            @if($activeUsers->contains($user))
                                                <span class="badge bg-success">Online</span>
                                            @else
                                                <span class="badge bg-secondary">Offline</span>
                                            @endif
                                            <br>
                                            <!-- Menampilkan status aktif/tidak aktif -->
                                            @if($user->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->id !== auth()->id()) <!-- Cek untuk tidak menampilkan tombol bagi user yang sedang login -->
                                                @if($user->is_active)
                                                    <form action="{{ route('users.deactivate', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-lock"></i> Deactivate
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('users.activate', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to activate this user?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="bi bi-unlock"></i> Activate
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }} <!-- Pastikan menggunakan kelas pagination yang tepat -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
