document.getElementById('register-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Ambil data input
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Validasi apakah password sama dengan konfirmasi password
    if (password !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Password dan konfirmasi password tidak cocok!',
        });
        return; // Jangan lanjutkan pengiriman jika tidak valid
    }

    // Jika valid, siapkan data untuk dikirimkan ke server
    const formData = new FormData(this);

    fetch('db/functions/register.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? 'Berhasil' : 'Gagal',
                text: data.message,
            }).then(result => {
                if (data.status === 'success' && result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
            });
        });
});