@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- Clean & Native Styling --}}
    <style>
        :root {
            --arb-gold: #DDB02C;
            --arb-gold-dark: #b88d18;
        }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .table > :not(caption) > * > * {
            padding: 0.75rem 1rem;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            color: #6c757d;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 0.75rem 1.25rem;
        }
        .nav-tabs .nav-link:hover {
            color: #212529;
            border-color: transparent;
        }
        .nav-tabs .nav-link.active {
            color: #212529;
            background-color: transparent;
            border-color: transparent transparent var(--arb-gold) transparent;
        }

        .btn-gold {
            background-color: var(--arb-gold);
            border-color: var(--arb-gold);
            color: #212529;
            font-weight: 600;
        }
        .btn-gold:hover {
            background-color: var(--arb-gold-dark);
            border-color: var(--arb-gold-dark);
            color: #ffffff;
        }
    </style>

    {{-- 1. PAGE HEADER --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h4 class="fw-bold mb-1 text-dark">Dashboard Admin</h4>
            <p class="text-muted mb-0 small">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan operasional Arbain Travel.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex align-items-center gap-2">
                <span class="badge bg-white text-dark border px-3 py-2 fw-semibold shadow-sm">
                    <i class='bx bx-calendar me-1' style="color: var(--arb-gold); font-size: 1rem; vertical-align: middle;"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
                <a href="{{ route('laporan-keuangan.index') }}" class="btn btn-sm btn-dark px-3 py-2 shadow-sm fw-semibold">
                    <i class='bx bx-file-blank me-1' style="vertical-align: middle;"></i> Laporan Keuangan
                </a>
            </div>
        </div>
    </div>

    {{-- 2. STAT CARDS (ROW OF 4) --}}
    <div class="row g-3 mb-4">
        <!-- 1. Total Pemasukan -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Total Omset</span>
                            <div class="stat-icon" style="background-color: #fef3c7; color: #b45309;">
                                <i class='bx bx-wallet'></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center small">
                        <span class="text-muted">Bulan ini:</span>
                        <span class="badge px-2 py-1" style="background-color: #dcfce7; color: #15803d; font-weight: 600;">
                            +Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Persetujuan Jamaah -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-sm h-100 {{ $totalJamaahPending > 0 ? 'border-danger' : '' }}">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Persetujuan Jamaah</span>
                            <div class="stat-icon" style="{{ $totalJamaahPending > 0 ? 'background-color: #fee2e2; color: #dc2626;' : 'background-color: #f1f5f9; color: #475569;' }}">
                                <i class='bx bx-user-check'></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">
                            {{ $totalJamaahPending }}
                            <span class="fs-6 fw-normal {{ $totalJamaahPending > 0 ? 'text-danger' : 'text-muted' }}">
                                {{ $totalJamaahPending > 0 ? 'Menunggu' : 'Tuntas' }}
                            </span>
                        </h4>
                    </div>
                    <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center small">
                        <span class="text-muted">Total {{ $totalJamaah }} Jamaah</span>
                        <a href="{{ route('jamaah.admin.index') }}" class="fw-semibold text-decoration-none {{ $totalJamaahPending > 0 ? 'text-danger' : 'text-primary' }}">
                            {{ $totalJamaahPending > 0 ? 'Verifikasi →' : 'Lihat Data →' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Pembayaran Masuk -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-sm h-100 {{ $pendingPayments > 0 ? 'border-warning' : '' }}">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Pembayaran Pending</span>
                            <div class="stat-icon" style="{{ $pendingPayments > 0 ? 'background-color: #fef3c7; color: #d97706;' : 'background-color: #dcfce7; color: #16a34a;' }}">
                                <i class='bx bx-credit-card'></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">
                            {{ $pendingPayments }}
                            <span class="fs-6 fw-normal text-muted">Transaksi</span>
                        </h4>
                    </div>
                    <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center small">
                        <span class="text-muted">{{ $approvedPayments }} Berhasil</span>
                        <a href="{{ route('admin.payments.index') }}" class="fw-semibold text-primary text-decoration-none">
                            Lihat Pembayaran &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Kejar Lunas & Kuota -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Kejar Lunas (H-60)</span>
                            <div class="stat-icon" style="background-color: #e0f2fe; color: #0284c7;">
                                <i class='bx bx-alarm-exclamation'></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">
                            {{ $totalJamaahKejarLunas }}
                            <span class="fs-6 fw-normal text-muted">Jamaah</span>
                        </h4>
                    </div>
                    <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center small">
                        <span class="text-muted">{{ $packagesWithFewerJamaah }} Paket Tersedia</span>
                        <a href="{{ route('listjamaah.index') }}" class="fw-semibold text-decoration-none text-dark">
                            Pantau Tagihan &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. CHARTS ROW (8 : 4) --}}
    <div class="row g-3 mb-4">
        <!-- Main Line Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class='bx bx-line-chart me-1' style="color: var(--arb-gold); vertical-align: middle;"></i> Grafik Omset & Transaksi Bulanan
                    </h6>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" id="btnModeRevenue" onclick="switchChartMode('revenue')">Omset (Rp)</button>
                            <button type="button" class="btn btn-outline-secondary" id="btnModeCount" onclick="switchChartMode('count')">Frekuensi</button>
                        </div>
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="m-0">
                            <select name="year" id="yearSelect" class="form-select form-select-sm" style="min-width: 135px;" onchange="this.form.submit()">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                        Tahun {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div style="height: 250px; position: relative;">
                        <canvas id="mainAnalyticsChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between text-muted small">
                    <span>Total Transaksi Tahun {{ $selectedYear }}: <strong>{{ array_sum($monthlyTransactions) }}</strong></span>
                    <span>Total Pemasukan: <strong class="text-success">Rp {{ number_format(array_sum($monthlyRevenue), 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class='bx bx-pie-chart-alt-2 text-info me-1' style="vertical-align: middle;"></i> Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body p-3 d-flex flex-column justify-content-center">
                    <div style="height: 180px; position: relative;">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <span><i class='bx bxs-circle me-1' style="color: var(--arb-gold); font-size: 10px;"></i> Transfer Bank:</span>
                            <strong class="text-dark">{{ $paymentMethods['transfer'] ?? 0 }} Transaksi</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span><i class='bx bxs-circle text-success me-1' style="font-size: 10px;"></i> Tunai (Cash):</span>
                            <strong class="text-dark">{{ $paymentMethods['cash'] ?? 0 }} Transaksi</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. OPERATIONAL QUEUE & RIGHT WIDGETS (8 : 4) --}}
    <div class="row g-3">
        <!-- Left: Action Verification Queue Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    {{-- Native Card Header Tabs --}}
                    <div class="card-header bg-white border-bottom p-0">
                        <ul class="nav nav-tabs card-header-tabs m-0 px-3" id="queueTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-jamaah-btn" data-bs-toggle="tab" data-bs-target="#content-jamaah" type="button" role="tab">
                                    <i class='bx bx-user-pin me-1' style="vertical-align: middle;"></i> Verifikasi Jamaah
                                    @if($totalJamaahPending > 0)
                                        <span class="badge bg-danger ms-1">{{ $totalJamaahPending }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-payments-btn" data-bs-toggle="tab" data-bs-target="#content-payments" type="button" role="tab">
                                    <i class='bx bx-credit-card me-1' style="vertical-align: middle;"></i> Bukti Pembayaran
                                    @if($pendingPayments > 0)
                                        <span class="badge bg-warning text-dark ms-1">{{ $pendingPayments }}</span>
                                    @endif
                                </button>
                            </li>
                        </ul>
                    </div>

                    {{-- Tab Contents --}}
                    <div class="tab-content" id="queueTabsContent">
                        <!-- Tab 1: Jamaah Pending -->
                        <div class="tab-pane fade show active" id="content-jamaah" role="tabpanel">
                            @if($recentPendingJamaah->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Nama Jamaah</th>
                                                <th>Paket</th>
                                                <th>Mitra Agen</th>
                                                <th class="text-center" style="width: 120px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPendingJamaah as $index => $j)
                                                <tr>
                                                    <td class="text-muted small">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $j->nama }}</div>
                                                        <small class="text-muted">NIK: {{ $j->nik ?? $j->no_paspor ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $j->package->name ?? 'Paket' }}
                                                        </span>
                                                    </td>
                                                    <td class="small text-secondary">{{ $j->referensi->name ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('jamaah.admin.index') }}" class="btn btn-sm btn-outline-primary py-1 px-3">
                                                            Verifikasi
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class='bx bx-check-circle text-success fs-1 d-block mb-1'></i>
                                    <h6 class="fw-bold text-dark mb-1">Semua Berkas Jamaah Telah Diverifikasi</h6>
                                    <small class="text-muted">Tidak ada pengajuan jamaah yang sedang tertunda.</small>
                                </div>
                            @endif
                        </div>

                        <!-- Tab 2: Pembayaran Pending -->
                        <div class="tab-pane fade" id="content-payments" role="tabpanel">
                            @if($recentPendingPayments->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Nama Jamaah</th>
                                                <th>Nominal</th>
                                                <th>Metode</th>
                                                <th class="text-center" style="width: 120px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPendingPayments as $index => $pay)
                                                <tr>
                                                    <td class="text-muted small">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $pay->jamaah->nama ?? 'Jamaah #' . $pay->jamaah_id }}</div>
                                                        <small class="text-muted">{{ $pay->jamaah->package->name ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-success">Rp {{ number_format($pay->amount, 0, ',', '.') }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border text-capitalize">{{ $pay->payment_method }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary py-1 px-3">
                                                            Review
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class='bx bx-check-double text-success fs-1 d-block mb-1'></i>
                                    <h6 class="fw-bold text-dark mb-1">Semua Pembayaran Telah Diproses</h6>
                                    <small class="text-muted">Tidak ada bukti transaksi yang sedang menunggu persetujuan.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="card-footer bg-white border-top py-2.5 px-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Menampilkan 5 pengajuan terkini</small>
                    <a href="{{ route('jamaah.admin.index') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold p-0">
                        Lihat Seluruh Pengajuan ({{ $totalJamaahPending }}) &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: 2 Stacked Widgets -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-3 h-100">
                <!-- Widget 1: Keberangkatan Terdekat -->
                <div class="card shadow-sm flex-grow-1">
                    <div class="card-header bg-white py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                        <h6 class="fw-bold mb-0 text-dark small text-uppercase">
                            <i class='bx bx-calendar-event me-1' style="color: var(--arb-gold); vertical-align: middle;"></i> Keberangkatan Terdekat
                        </h6>
                        <a href="{{ route('packages.index') }}" class="small text-decoration-none text-muted">Semua &rarr;</a>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingPackages->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($upcomingPackages->take(3) as $pkg)
                                    @php
                                        $depDate = \Carbon\Carbon::parse($pkg->departure_date);
                                        $daysLeft = (int) \Carbon\Carbon::now()->diffInDays($depDate, false);
                                    @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $pkg->name }}</div>
                                            <small class="text-muted">
                                                <i class='bx bx-calendar me-1'></i> {{ $depDate->translatedFormat('d M Y') }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $daysLeft <= 30 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                {{ $daysLeft > 0 ? $daysLeft . ' Hari Lagi' : 'Hari Ini' }}
                                            </span>
                                            <div class="text-muted small mt-0.5" style="font-size: 11px;">
                                                {{ $pkg->jamaahs_count }} Jamaah
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-3 text-center text-muted small">
                                Belum ada paket keberangkatan mendatang.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Widget 2: Top Agen -->
                <div class="card shadow-sm flex-grow-1">
                    <div class="card-header bg-white py-2.5 px-3 d-flex justify-content-between align-items-center border-bottom">
                        <h6 class="fw-bold mb-0 text-dark small text-uppercase">
                            <i class='bx bx-trophy me-1' style="color: var(--arb-gold); vertical-align: middle;"></i> Top Mitra Agen
                        </h6>
                        <a href="{{ route('users.index') }}" class="small text-decoration-none text-muted">Mitra &rarr;</a>
                    </div>
                    <div class="card-body p-0">
                        @if($topAgents->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($topAgents->take(3) as $idx => $agen)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge rounded-pill {{ $idx == 0 ? 'bg-warning text-dark' : ($idx == 1 ? 'bg-secondary' : 'bg-light text-dark border') }}" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $idx + 1 }}
                                            </span>
                                            <div>
                                                <div class="fw-bold text-dark small">{{ $agen->name }}</div>
                                                <small class="text-muted">{{ $agen->phone ?? '@' . $agen->username }}</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-dark border">
                                            {{ $agen->jamaahs_count }} Jamaah
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-3 text-center text-muted small">
                                Belum ada data mitra agen.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        var dataRevenue = @json(array_values($monthlyRevenue));
        var dataCount = @json(array_values($monthlyTransactions));
        var year = {{ $selectedYear }};

        // 1. Line Chart
        var ctxLine = document.getElementById('mainAnalyticsChart').getContext('2d');
        window.myLineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Omset ' + year + ' (Rp)',
                    data: dataRevenue,
                    borderColor: '#DDB02C',
                    backgroundColor: 'rgba(221, 176, 44, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#DDB02C',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (window.currentMode === 'count') {
                                    return ctx.parsed.y + ' Transaksi';
                                }
                                return 'Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                if (window.currentMode === 'count') return val;
                                if (val >= 1000000) return (val / 1000000) + ' Jt';
                                return val;
                            }
                        }
                    }
                }
            }
        });

        window.currentMode = 'revenue';
        window.switchChartMode = function(mode) {
            window.currentMode = mode;
            var btnRev = document.getElementById('btnModeRevenue');
            var btnCount = document.getElementById('btnModeCount');
            if (mode === 'revenue') {
                btnRev.classList.add('active');
                btnCount.classList.remove('active');
                window.myLineChart.data.datasets[0].label = 'Omset ' + year + ' (Rp)';
                window.myLineChart.data.datasets[0].data = dataRevenue;
                window.myLineChart.data.datasets[0].borderColor = '#DDB02C';
                window.myLineChart.data.datasets[0].backgroundColor = 'rgba(221, 176, 44, 0.1)';
            } else {
                btnCount.classList.add('active');
                btnRev.classList.remove('active');
                window.myLineChart.data.datasets[0].label = 'Transaksi ' + year;
                window.myLineChart.data.datasets[0].data = dataCount;
                window.myLineChart.data.datasets[0].borderColor = '#0d6efd';
                window.myLineChart.data.datasets[0].backgroundColor = 'rgba(13, 110, 253, 0.1)';
            }
            window.myLineChart.update();
        };

        // 2. Donut Chart
        var ctxDonut = document.getElementById('paymentMethodChart').getContext('2d');
        var transfer = {{ $paymentMethods['transfer'] ?? 0 }};
        var cash = {{ $paymentMethods['cash'] ?? 0 }};

        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Transfer Bank', 'Tunai'],
                datasets: [{
                    data: [transfer, cash],
                    backgroundColor: ['#DDB02C', '#198754'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection
