@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Riwayat Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table id="payment-history-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama Jamaah</th>
                                <th>Paket</th>
                                <th>Jumlah Pembayaran</th>
                                <th>Harga Paket</th>
                                <th>Tunggakan</th>
                                <th>Status Pembayaran</th>
                                <th>Tanggal Keberangkatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan dimuat oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('agen.payments.history.data') }}',
                columns: [
                    { data: 'jamaah_name', name: 'jamaah_name' },
                    { data: 'package_name', name: 'package_name' },
                    { data: 'price', name: 'price' },
                    { data: 'due', name: 'due' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'departure_date', name: 'departure_date' }
                ]
            });
        });
    </script>
@endsection

