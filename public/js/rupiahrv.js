document.getElementById('price').addEventListener('input', function (e) {
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
    document.getElementById('hidden-price').value = value.replace(/\./g, '');
});

// Memastikan harga yang tersimpan tanpa titik pada submit
document.querySelector('form').addEventListener('submit', function (e) {
    let priceInput = document.getElementById('price');
    let hiddenPriceInput = document.getElementById('hidden-price');

    // Memastikan harga tanpa format titik
    hiddenPriceInput.value = priceInput.value.replace(/\./g, '');
});
