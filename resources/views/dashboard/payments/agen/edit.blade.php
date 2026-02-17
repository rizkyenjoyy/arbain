@extends('dashboard.layouts.main')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Edit Payment</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Payment Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="jamaah_id" class="form-label">Select Jamaah</label>
                            <select name="jamaah_id" class="form-select" required>
                                <option value="{{ $payment->jamaah_id }}">{{ $payment->jamaah->name }} - Package: {{ $payment->jamaah->package->name }}</option>
                                @foreach($jamaahs as $jamaah)
                                    @if($jamaah->id != $payment->jamaah_id)
                                        <option value="{{ $jamaah->id }}">{{ $jamaah->name }} - Package: {{ $jamaah->package->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Payment Amount</label>
                            <input type="number" name="amount" class="form-control" id="amount" value="{{ $payment->amount }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="{{ $payment->payment_method }}" selected>{{ ucfirst($payment->payment_method) }}</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <div class="mb-3" id="payment_proof_section" style="{{ $payment->payment_method === 'transfer' ? '' : 'display: none;' }}">
                            <label for="payment_proof" class="form-label">Upload Payment Proof</label>
                            <input type="file" name="payment_proof" class="form-control" id="payment_proof" accept=".jpg,.png,.pdf">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn" style="background-color: #DDB02C; color: white;">Update Payment</button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function () {
        var paymentProofSection = document.getElementById('payment_proof_section');
        paymentProofSection.style.display = this.value === 'transfer' ? 'block' : 'none';
    });
</script>

@endsection
