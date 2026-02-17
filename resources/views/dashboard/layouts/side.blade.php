
<!-- top navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="offcanvasExample">
            <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
        </button>
        <a class="navbar-brand me-auto ms-lg-0 ms-3 text-uppercase fw-bold" href="#">
            <img src="{{ asset('img/arbl.png') }}" alt="Logo" width="30" height="30" class="d-inline-block align-middle">
            ARBAIND
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar"
            aria-controls="topNavBar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNavBar">
            <form class="d-flex ms-auto my-3 my-lg-0">
                <div class="input-group">
                    {{-- <input class="form-control" type="search" placeholder="Search" aria-label="Search" /> --}}
                    {{-- <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button> --}}
                </div>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle ms-2" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-person-fill"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- offcanvas -->
<div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="sidebar">
    <div class="offcanvas-body p-0">
        <nav class="navbar-dark">
            <ul class="navbar-nav">
                @if(auth()->user()->can('access admin'))
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 mt-3">
                        Menu Utama
                    </div>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/admin') ? 'active' : '' }}">
                        <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 mb-3">
                        Manajemen Data
                    </div>
                </li>
                <li>
                    <a href="{{ route('users.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('users') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bxs-user-pin'></i></span>
                        <span>Kelola User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('packages.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('packages') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bxs-package'></i></span>
                        <span>Kelola Paket</span>
                    </a>
                </li>
                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 mb-3">
                        Manajemen Persetujuan
                    </div>
                </li>
                <li>
                    <a href="{{ route('jamaah.admin.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/jamaah/admin') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bx-user-pin'></i></span>
                        <span>Persetujuan Jamaah </span>

                        @php
                            // Filter notifikasi yang terkait dengan JamaahAddedNotification
                            $jamaahNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewJamaahAddedNotification');
                        @endphp

                        @if ($jamaahNotifications->count() > 0)
                            <span class="badge bg-danger"> {{ $jamaahNotifications->count() }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.payments.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/payments/admin') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bx-credit-card'></i></span>
                        <span>Persetujuan Pembayaran</span>

                        @php
                            $unreadPaymentsCount = Auth::user()->unreadNotifications->where('type', 'App\Notifications\PaymentAddedNotification')->count();
                        @endphp

                        @if($unreadPaymentsCount > 0)
                            <span class="badge bg-danger">{{ $unreadPaymentsCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('listjamaah.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/listjamaah') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bx-user-check' ></i></span>
                        <span>Daftar Jamaah</span>
                    </a>
                </li>
                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 mb-3">
                        Manajemen Keuangan
                    </div>
                </li>
                <li>
                    <a href="{{ route('commissions.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/commissions') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bx-money-withdraw'></i></span>
                        <span>Pembayaran Komisi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan-keuangan.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('laporan-keuangan') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bx-line-chart'></i></span>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>

                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 mb-3">
                        Account
                    </div>
                </li>
                <li>
                    <a href="{{ route('akun_saya.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('akun_saya.index') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bxs-user'></i></i></span>
                        <span>Akun Saya</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('access agen'))
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 mt-3">
                        Menu Utama
                    </div>
                </li>
                <li>
                    <a href="{{ route('agen.dashboard') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/agen') ? 'active' : '' }}">
                        <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                        <span>Dashboard Agen</span>
                    </a>
                </li>
                <!-- Sidebar khusus agen -->
                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <a href="{{ route('jamaah.agen.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/jamaah/agen') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bxs-contact'></i></span>
                        <span>Pengajuan Jamaah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/payments/agen') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bx-money'></i></span>
                        <span>Pengajuan Pembayaran</span>
                    </a>
                </li>
                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <a href="{{ route('riwayat.pembayaran.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('jamaah-saya') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bxs-user-check'></i></span>
                        <span>Jamaah Saya</span>
                        @php
                            $jamaahNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\JamaahApprovedNotification')->count();
                            $paymentNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\PaymentApprovedNotification')->count();
                            $totalNotifications = $jamaahNotifications + $paymentNotifications;
                        @endphp
                        @if($totalNotifications > 0)
                            <span class="badge badge-danger">{{ $totalNotifications }}</span>
                        @endif
                    </a>
                </li>


                <li>
                    <a href="{{ route('commissions.agen.index') }}" class="nav-link sidebar-link px-3 {{ Request::is('dashboard/commissions/agen') ? 'active' : '' }}">
                        <span class="me-2">
                            <i class='bx bx-money'></i>
                        </span>
                        <span>Komisi Saya</span>

                        <!-- Badge merah jika ada notifikasi baru -->
                        @php
                            $commissionNotificationsCount = auth()->user()->unreadNotifications->where('type', 'App\Notifications\CommissionAddedNotification')->count();
                        @endphp

                        @if($commissionNotificationsCount > 0)
                            <span class="badge bg-danger">{{ $commissionNotificationsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="my-4">
                    <hr class="dropdown-divider bg-light" />
                </li>
                <li>
                    <a href="{{ route('akun.agen') }}" class="nav-link sidebar-link px-3 {{ Request::is('akun-saya/agen') ? 'active' : '' }}">
                        <span class="me-2"><i class='bx bxs-user'></i></i></span>
                        <span>Akun Saya</span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>


