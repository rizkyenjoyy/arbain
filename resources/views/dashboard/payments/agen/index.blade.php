@extends('dashboard.layouts.main')

@section('content')

<div class="container-fluid p-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Daftar Pembayaran</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-white" style="position: relative;">
                    <h5 class="mb-0" style="color: #DDB02C;">Pembayaran Anda</h5>
                    <a href="{{ route('payments.create') }}" class="btn btn-success" style="color: white;">
                        <i class='bx bxs-add-to-queue'></i> Pembayaran Baru
                    </a>

                    <!-- Garis bawah header -->
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background-color: #DDB02C;"></div>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by ID or Nama Jamaah" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    @if($payments->isEmpty())
                        <div class="alert alert-warning">Tidak ada pembayaran yang tersedia.</div>
                    @else
                        <table class="table table-bordered">
                            <thead style="background-color: #DFFFD6;">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Jamaah</th>
                                    <th>Referensi Agen</th>
                                    <th>Jumlah Pembayaran</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->jamaah->nama }}</td>
                                    <td>{{ $payment->jamaah->referensi->name }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>
                                        @if($payment->payment_proof)
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#paymentProofModal" data-img="{{ asset('storage/' . $payment->payment_proof) }}">
                                                <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran" style="width: 100px; height: 100px; cursor: pointer;">
                                            </a>
                                        @else
                                            Tidak Ada
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($payment->status) }}</td>
                                    <td>
                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?')"><i class='bx bxs-trash-alt'></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="card-footer">
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
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
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#paymentProofModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Gambar yang di-klik
            var imgSrc = button.data('img'); // Ambil URL gambar dari data-img
            var modal = $(this);
            modal.find('#modalImage').attr('src', imgSrc); // Set src modal image
        });
    });
</script>

@endsection
