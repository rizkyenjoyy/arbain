document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('metode_pembayaran');
    const paymentProofInput = document.getElementById('bukti_transfer');

    function togglePaymentProof() {
        if (paymentMethodSelect.value === 'transfer') {
            paymentProofInput.style.display = 'block'; // Tampilkan input bukti transfer
        } else {
            paymentProofInput.style.display = 'none'; // Sembunyikan input bukti transfer
            paymentProofInput.value = ''; // Kosongkan input jika metode cash
        }
    }

    // Tambahkan event listener untuk perubahan pada select metode pembayaran
    paymentMethodSelect.addEventListener('change', togglePaymentProof);

    // Panggil fungsi untuk mengatur tampilan awal
    togglePaymentProof();
});
