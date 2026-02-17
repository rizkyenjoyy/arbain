<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        @media print {
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                font-size: 12px;
            }
            .container {
                width: 100%;
                padding: 20px;
            }
        }
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f9;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .table-custom {
            margin-top: 20px;
            border: 1px solid #ddd;
        }
        .table-custom th {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .table-custom td {
            padding: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row invoice-header align-items-center">
            <div class="col-md-12">
                <h2 class="invoice-title">Invoice Pembayaran</h2>
                <p><strong>Nomor Invoice:</strong> {{ $invoiceNumber }}</p>
                <p><strong>Tanggal:</strong> {{ now()->format('d F Y') }}</p>
            </div>
        </div>

        @foreach ($jamaahs as $jamaah)
        <div class="row my-4">
            <div class="col-md-6">
                <h5>Tagihan Kepada:</h5>
                <p><strong>Nama:</strong> {{ $jamaah->nama }}</p>
                <p><strong>Referensi:</strong> {{ $jamaah->nik }}</p>
                <p><strong>Alamat:</strong> {{ $jamaah->alamat }}</p>
                <p><strong>Telepon:</strong> {{ $jamaah->kontak }}</p>
            </div>
        </div>
        @endforeach

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-custom">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Paket</th>
                            <th>Unit</th>
                            <th>Harga</th>
                            <th>Total Pembayaran</th>
                            <th>Tunggakan</th>
                            <th>Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jamaahs as $jamaah)
                        <tr>
                            <td>{{ $jamaah->nama }}</td>
                            <td>{{ $jamaah->package->name }}</td>
                            <td>1</td>
                            <td>Rp {{ number_format($jamaah->package->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($jamaah->total_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($jamaah->getSisaPembayaranAttribute(), 0, ',', '.') }}</td>
                            <td>{{ $jamaah->getJatuhTempoAttribute()->format('d F Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row invoice-footer">
            <div class="col-md-12">
                <p class="footer-notes"><strong>Catatan:</strong> Terima kasih atas pembayaran Anda. Jika ada pertanyaan terkait invoice ini, silakan hubungi customer service kami.</p>
                <div class="text-signature">
                    <p>Hormat Kami,</p>
                    <p>PT. Alyusro Bandung Indonesia</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
