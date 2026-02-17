@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Daftar Pembayaran Jamaah</h2>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="position: relative;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #DDB02C;">Pembayaran Jamaah</h5>
                        <form action="{{ route('admin.payments.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search by Jamaah or Package" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
                </div>
                <!-- Bagian Notifikasi -->
                <div class="mb-3">
                    @php
                        $paymentNotifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\PaymentAddedNotification');
                    @endphp

                    @if ($paymentNotifications->count() > 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Notifikasi Baru!</strong> Anda memiliki {{ $paymentNotifications->count() }} notifikasi pembayaran baru.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>


                <div class="card-body">
                    @if($payments->isEmpty())
                        <div class="text-center alert alert-warning">Tidak ada pembayaran yang tersedia.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead style="background-color: #DFFFD6;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Jamaah</th>
                                        <th>Referensi</th>
                                        <th>Nama Paket</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Status</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->jamaah->nama }}</td>
                                            <td>{{ $payment->jamaah->referensi->name ?? 'Tidak Ada' }}</td>
                                            <td>{{ $payment->jamaah->package->name ?? 'Tidak Ada' }}</td>
                                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($payment->status) }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>
                                                @if($payment->payment_proof)
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#paymentProofModal" data-img="{{ asset('storage/' . $payment->payment_proof) }}">
                                                        <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran" style="width: 50px; height: 50px; cursor: pointer;">
                                                    </a>
                                                @else
                                                    Tidak Ada
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->status === 'pending')
                                                    <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="bi bi-check-circle"></i> Approve
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                            <i class="bi bi-x-circle"></i> Reject
                                                        </button>
                                                    </form>
                                                @else
                                                    <span>{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $payments->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for viewing payment proof -->
    <div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentProofModalLabel">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="proofImage" src="" alt="Bukti Pembayaran" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script to handle displaying the payment proof in the modal
    document.addEventListener('DOMContentLoaded', function() {
        const proofModal = document.getElementById('paymentProofModal');
        proofModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const imageSrc = button.getAttribute('data-img'); // Extract info from data-* attributes
            const proofImage = proofModal.querySelector('#proofImage');
            proofImage.src = imageSrc; // Update the modal's content
        });
    });
</script>

@endsection
