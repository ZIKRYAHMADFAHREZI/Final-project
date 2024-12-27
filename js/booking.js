
document.getElementById('pesanButton').addEventListener('click', function () {
    const startDate = startDateInput.value;
    const selectedRoom = document.querySelector('input[name="number_room"]:checked');

    if (!startDate) {
        Swal.fire({
            title: 'Tanggal Belum Dipilih!',
            text: 'Silakan pilih tanggal mulai terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (!selectedRoom) {
        Swal.fire({
            title: 'Kamar Belum Dipilih!',
            text: 'Silakan pilih kamar terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const payMethods = JSON.parse(document.getElementById('payMethodsData').value);

    Swal.fire({
        title: 'Pilih Metode Pembayaran',
        input: 'radio',
        inputOptions: payMethods.reduce(function (options, method) {
            options[method.id_pay_method] = method.method;
            return options;
        }, {}),
        inputValidator: (value) => {
            return !value && 'Anda harus memilih metode pembayaran!';
        },
        showCancelButton: true,
        confirmButtonText: 'Konfirmasi',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('id_pay_method').value = result.value;
            document.getElementById('bookingForm').submit();
        }
    });
});