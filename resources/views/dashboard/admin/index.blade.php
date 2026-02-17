@extends('dashboard.layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <!-- Total Jamaah -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-users fa-2x" style="color: #2980b9;"></i>
                    </div>
                    <h5 class="card-title" style="color: #2980b9; font-size: 1.2rem; font-weight: 600;">Total Jamaah</h5>
                    <p class="display-4" style="color: #2980b9; font-weight: 700;">{{ $totalJamaah }}</p>
                </div>
            </div>
        </div>

        <!-- Total Agen -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-user-tie fa-2x" style="color: #27ae60;"></i>
                    </div>
                    <h5 class="card-title" style="color: #27ae60; font-size: 1.2rem; font-weight: 600;">Total Agen</h5>
                    <p class="display-4" style="color: #27ae60; font-weight: 700;">{{ $totalAgen }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-exclamation-circle fa-2x" style="color: #c0392b;"></i>
                    </div>
                    <h5 class="card-title" style="color: #c0392b; font-size: 1.2rem; font-weight: 600;">Pembayaran Pending</h5>
                    <p class="display-4" style="color: #c0392b; font-weight: 700;">{{ $pendingPayments }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Jamaah -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-user-clock fa-2x" style="color: #e67e22;"></i>
                    </div>
                    <h5 class="card-title" style="color: #e67e22; font-size: 1.2rem; font-weight: 600;">Jamaah Pending</h5>
                    <p class="display-4" style="color: #e67e22; font-weight: 700;">{{ $totalJamaahPending }}</p>
                </div>
            </div>
        </div>

        <!-- Jamaah Yang Belum Bayar -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-wallet fa-2x" style="color: #e67e22;"></i>
                    </div>
                    <h5 class="card-title" style="color: #e67e22; font-size: 1.2rem; font-weight: 600;">Jamaah Yang Belum Bayar</h5>
                    <p class="display-4" style="color: #e67e22; font-weight: 700;">{{ $totalJamaahUnpaid }}</p>
                </div>
            </div>
        </div>

        <!-- Jamaah Kejar Lunas (H-60) -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-bell fa-2x" style="color: #d35400;"></i>
                    </div>
                    <h5 class="card-title" style="color: #d35400; font-size: 1.2rem; font-weight: 600;">Jamaah Kejar Lunas (H-60)</h5>
                    <p class="display-4" style="color: #d35400; font-weight: 700;">{{ $totalJamaahKejarLunas }}</p>
                </div>
            </div>
        </div>

        <!-- Packages with <= 12 Jamaah -->
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card shadow-lg" style="background-color: #f4f6f9; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <i class="fas fa-box fa-2x" style="color: #8e44ad;"></i>
                    </div>
                    <h5 class="card-title" style="color: #8e44ad; font-size: 1.2rem; font-weight: 600;">Paket Dengan Kursi Tersedia</h5>
                    <p class="display-4" style="color: #8e44ad; font-weight: 700;">{{ $packagesWithFewerJamaah }}</p>
                </div>
            </div>
        </div>


    </div>

    <!-- Grafik Transaksi Bulanan -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="card-title text-center" style="font-weight: 600; color: #34495e;">Grafik Transaksi Bulanan</h5>

                    <!-- Dropdown Tahun -->
                    <form action="{{ url('dashboard/admin') }}" method="GET" class="mb-3 text-center">
                        <label for="year" style="font-weight: 500; color: #7f8c8d;">Pilih Tahun:</label>
                        <select name="year" id="year" class="form-control w-auto d-inline-block" style="border-radius: 8px; padding: 5px;" onchange="this.form.submit()">
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Grafik -->
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('transactionsChart').getContext('2d');
        var chartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Total Transaksi {{ $selectedYear }}',
                data: @json(array_values($monthlyTransactions)),
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(52, 152, 219, 1)',
                pointHoverRadius: 7,
                pointHoverBackgroundColor: 'rgba(52, 152, 219, 1)',
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#34495e',
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#2c3e50',
                        titleColor: '#ecf0f1',
                        bodyColor: '#ecf0f1',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#2c3e50',
                        },
                        grid: {
                            color: '#ecf0f1',
                        }
                    },
                    x: {
                        ticks: {
                            color: '#2c3e50',
                        },
                        grid: {
                            color: '#ecf0f1',
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
