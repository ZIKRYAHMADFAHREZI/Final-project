function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    icon.addEventListener('click', () => {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.querySelector('i').classList.toggle('fa-eye-slash');
    });
}

togglePasswordVisibility('password_lama', 'toggle-password-lama');
togglePasswordVisibility('password_baru', 'toggle-password-baru');
togglePasswordVisibility('konfirmasi_password', 'toggle-konfirmasi-password');

document.getElementById('submitButton').addEventListener('click', function () {
    Swal.fire({
        title: 'Konfirmasi Ubah Data',
        text: "Apakah Anda yakin ingin mengubah password?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('updateForm').submit();
        }
    });
});