@extends('dashboard.layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <!-- Total Jamaah -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0" style="background-color: #f9fafc; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-users fa-3x" style="color: #3498db;"></i> <!-- Icon added here -->
                    </div>
                    <h5 class="card-title" style="color: #2c3e50; font-weight: 600;">Total Jamaah</h5>
                    <p class="display-4" style="color: #3498db; font-weight: bold;">{{ $totalJamaah }}</p>
                </div>
            </div>
        </div>

        <!-- Komisi yang sudah dibayarkan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0" style="background-color: #f9fafc; border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-hand-holding-usd fa-3x" style="color: #9b59b6;"></i> <!-- Icon added here -->
                    </div>
                    <h5 class="card-title" style="color: #2c3e50; font-weight: 600;">Komisi Dibayarkan</h5>
                    <p class="display-4" style="color: #9b59b6; font-weight: bold;">{{ $paidCommissions }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Transaksi Bulanan -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Grafik Transaksi Bulanan</h5>

                    <!-- Dropdown Tahun -->
                    <form action="{{ url('dashboard/agen') }}" method="GET" class="mb-3">
                        <label for="year">Pilih Tahun:</label>
                        <select name="year" id="year" onchange="this.form.submit()">
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
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
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
