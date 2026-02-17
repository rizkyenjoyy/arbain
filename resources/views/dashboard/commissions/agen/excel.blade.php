<table>
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Jamaah</th>
            <th scope="col">NIK</th>
            <th scope="col">Nama Paket</th>
            <th scope="col">Jumlah Komisi</th>
            <th scope="col">Harga Paket</th>
        </tr>
    </thead>
    <tbody>
        @foreach($commissions as $index => $commission)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $commission->jamaah->nama }}</td>
                <td>{{ $commission->jamaah->nik }}</td>
                <td>{{ $commission->jamaah->package->name }}</td>
                <td>Rp {{ number_format($commission->amount, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($commission->jamaah->package->price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
