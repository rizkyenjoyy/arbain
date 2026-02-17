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
                <h2 style="color: #DDB02C;">Create New Payment</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Payment Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="jamaah_search" class="form-label">Search Jamaah by Name/NIK</label>
                            <input type="text" id="jamaah_search" class="form-control" placeholder="Type name or NIK">
                            <input type="hidden" name="jamaah_id" id="jamaah_id" required>
                            <div id="jamaah_list" class="list-group" style="max-height: 150px; overflow-y: auto;"></div>
                        </div>

                        <!-- Informasi tambahan yang ditampilkan -->
                        <div class="mb-3">
                            <label for="package_name" class="form-label">Package Name</label>
                            <input type="text" id="package_name" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="outstanding" class="form-label">Sisa Tunggakan</label>
                            <input type="text" id="outstanding" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="days_left" class="form-label">Days Until Due</label>
                            <input type="text" id="days_left" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Payment Amount</label>
                            <input type="text" name="formatted_amount" class="form-control" id="amount" required>
                            <input type="hidden" id="hidden-amount" name="amount">
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="" selected disabled>-- Select Payment Method --</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <div class="mb-3" id="payment_proof_section" style="display: none;">
                            <label for="payment_proof" class="form-label">Upload Payment Proof</label>
                            <input type="file" name="payment_proof" class="form-control" id="payment_proof" accept=".jpg,.png,.pdf">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success" style="color: white;">Submit Payment</button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Search Jamaah using AJAX
        $('#jamaah_search').on('input', function() {
            let search = $(this).val();

            if (search.length >= 2) {
                $.ajax({
                    url: '{{ route("payments.searchJamaah") }}',
                    method: 'GET',
                    data: { search: search },
                    success: function(response) {
                        $('#jamaah_list').empty(); // Clear the list
                        if (response.length > 0) {
                            response.forEach(function(jamaah) {
                                $('#jamaah_list').append(`<a href="#" class="list-group-item list-group-item-action" data-id="${jamaah.id}" data-package="${jamaah.package_name}" data-outstanding="${jamaah.outstanding}" data-days_left="${jamaah.days_left}">${jamaah.nama} (NIK: ${jamaah.nik})</a>`);
                            });
                        } else {
                            $('#jamaah_list').append('<p class="list-group-item">No results found</p>');
                        }
                    }
                });
            } else {
                $('#jamaah_list').empty(); // Clear the list if less than 2 characters
            }
        });

        // Select Jamaah from list and populate additional fields
        $(document).on('click', '#jamaah_list a', function(e) {
            e.preventDefault();
            let jamaahId = $(this).data('id');
            let jamaahName = $(this).text();
            let packageName = $(this).data('package');
            let outstanding = $(this).data('outstanding');
            let daysLeft = $(this).data('days_left');

            $('#jamaah_search').val(jamaahName);
            $('#jamaah_id').val(jamaahId);
            $('#jamaah_list').empty(); // Clear the list

            // Set the additional fields
            $('#package_name').val(packageName);
            $('#outstanding').val(outstanding);
            $('#days_left').val(daysLeft !== null ? daysLeft + ' days left' : 'No due date');
        });

        // Show payment proof section if "Transfer" is selected
        document.getElementById('payment_method').addEventListener('change', function () {
            var paymentProofSection = document.getElementById('payment_proof_section');
            paymentProofSection.style.display = this.value === 'transfer' ? 'block' : 'none';
        });

        // Format currency for the amount field
        document.getElementById('amount').addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            e.target.value = rupiah;

            // Simpan angka asli tanpa pemisah ke dalam hidden input
            document.getElementById('hidden-amount').value = value.replace(/\./g, '');
        });

        // On form submit, ensure the amount is saved without dots
        document.querySelector('form').addEventListener('submit', function (e) {
            let amountInput = document.getElementById('amount');
            let hiddenAmountInput = document.getElementById('hidden-amount');

            // Remove dots before form submit
            hiddenAmountInput.value = amountInput.value.replace(/\./g, '');
        });
    });
</script>

@endsection
