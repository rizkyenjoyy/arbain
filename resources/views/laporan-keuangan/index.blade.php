@extends('dashboard.layouts.main')

@section('content')

<div class="container mt-4">
    <div class="row">
        <!-- Total Pemasukan -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Pemasukan</h5>
                    <p class="card-text">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Profit -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Kas</h5>
                    <p class="card-text">Rp. {{ number_format($totalProfit, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Komisi yang Dibayarkan -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Komisi Dibayarkan</h5>
                    <p class="card-text">Rp. {{ number_format($totalKomisi, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Pemasukan & Komisi -->
    <div class="card mt-5">
        <div class="card-header">
            Grafik Pemasukan dan Komisi
        </div>
        <div class="card-body">
            <canvas id="financeChart" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>

    <!-- Grafik Pemasukan per Bulan -->
    <div class="card mt-5">
        <div class="card-header">
            Grafik Pemasukan per Bulan
            <div class="float-end">
                <form method="GET" action="{{ route('laporan-keuangan.index') }}">
                    <div class="input-group">
                        <select class="form-select" name="year" onchange="this.form.submit()">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-select" name="month" onchange="this.form.submit()">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </form>
                <div class="float-end">
                    <a href="{{ route('laporan-keuangan.download-csv', ['year' => $selectedYear]) }}" class="btn btn-primary mb-2 float-end">
                        <i class='bx bx-export'></i> <span>Unduh CSV</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <canvas id="monthlyIncomeChart" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('financeChart').getContext('2d');
    var financeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Pemasukan', 'Total Kas', 'Total Pengeluaran'],
            datasets: [{
                label: 'Jumlah (Rp)',
                data: [{{ $totalPemasukan }}, {{ $totalProfit }}, {{ $totalPengeluaran }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx2 = document.getElementById('monthlyIncomeChart').getContext('2d');
    var monthlyIncomeChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: @json($months), // Mengambil list bulan dari controller
            datasets: [{
                label: 'Pemasukan Bulanan (Rp)',
                data: @json($monthlyIncomeData), // Mengambil data pemasukan bulanan
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Pemasukan (Rp)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
