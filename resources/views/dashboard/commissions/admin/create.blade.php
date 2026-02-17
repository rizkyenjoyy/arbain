@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Tambah Komisi Agen</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Form Tambah Komisi</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('commissions.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Jamaah yang sudah lunas -->
                        <div class="mb-3">
                            <label for="jamaah_id" class="form-label">Pilih Jamaah (Lunas)</label>
                            <select name="jamaah_id" id="jamaah_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jamaah --</option>
                                @foreach ($jamaahs as $jamaah)
                                    <option value="{{ $jamaah->id }}"
                                            data-nik="{{ $jamaah->nik }}"
                                            data-agen="{{ $jamaah->referensi->name ?? 'Tidak Ada' }}">
                                        {{ $jamaah->nama }} - Paket: {{ $jamaah->package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Informasi NIK dan Nama Agen -->
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK Jamaah</label>
                            <input type="text" name="nik" id="nik" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="agen_id" class="form-label">Nama Agen</label>
                            <input type="text" name="agen_id" id="agen_id" class="form-control" readonly>
                        </div>

                        <!-- Kolom Jumlah Komisi -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Komisi</label>
                            <input type="text" name="formatted_amount" id="amount" class="form-control" required>
                            <input type="hidden" id="hidden-amount" name="amount">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn" style="background-color: #DDB02C; color: white;">Simpan</button>
                            <a href="{{ route('commissions.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('jamaah_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var nikField = document.getElementById('nik');
        var agenField = document.getElementById('agen_id');

        // Ambil data NIK dan agen_id dari option yang dipilih
        nikField.value = selectedOption.getAttribute('data-nik');  // Ambil NIK
        agenField.value = selectedOption.getAttribute('data-agen');  // Ambil nama Agen
    });

    // Format angka input untuk Jumlah Komisi
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

    // Pada form submit, pastikan mengirim komisi tanpa titik
    document.querySelector('form').addEventListener('submit', function (e) {
        let amountInput = document.getElementById('amount');
        let hiddenAmountInput = document.getElementById('hidden-amount');

        // Memastikan komisi tanpa format titik
        hiddenAmountInput.value = amountInput.value.replace(/\./g, '');
    });
</script>

@endsection
