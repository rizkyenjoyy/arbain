<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>No. Paspor</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Kontak</th>
            <th>Paket</th>
            <th>Tanggal Keberangkatan</th>
            <th>Jatuh Tempo</th>
            <th>Total Pembayaran</th>
            <th>Tunggakan</th>
            <th>Referensi Agen</th>
            <th>Status Komisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jamaahs as $index => $jamaah)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $jamaah->nama }}</td>
            <td>{{ $jamaah->nik }}</td>
            <td>{{ $jamaah->no_paspor }}</td>
            <td>{{ $jamaah->tempat_lahir }}</td>
            <td>{{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d-m-Y') }}</td>
            <td>{{ $jamaah->jenis_kelamin }}</td>
            <td>{{ $jamaah->alamat }}</td>
            <td>{{ $jamaah->kontak }}</td>
            <td>{{ $jamaah->package->name }}</td>
            <td>{{ \Carbon\Carbon::parse($jamaah->package->departure_date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($jamaah->jatuh_tempo)->format('d-m-Y') }}</td>
            <td>Rp {{ number_format($jamaah->total_amount, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($jamaah->sisa_pembayaran, 0, ',', '.') }}</td>
            <td>{{ $jamaah->referensi->name ?? 'Tidak Ada' }}</td>
            <td>
                @if ($jamaah->commission)
                    Sudah DIbayar
                @else
                    Belum Dibayar
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
