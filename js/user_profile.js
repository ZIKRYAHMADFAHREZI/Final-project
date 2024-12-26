// Menambahkan event listener untuk mendeteksi perubahan pada form
let formChanged = false;

const form = document.querySelector('form');
const formElements = form.querySelectorAll('input, select, textarea');

// Menandai perubahan data pada form
formElements.forEach(element => {
    element.addEventListener('input', () => {
        formChanged = true; // Form telah berubah
    });
});

// Menambahkan event listener pada tombol submit
document.getElementById('submitButton').addEventListener('click', async function (event) {
    event.preventDefault();

    // Menampilkan SweetAlert untuk konfirmasi
    const result = await Swal.fire({
        title: 'Konfirmasi Ubah Data',
        text: "Apakah Anda yakin ingin mengubah data?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        // Jika pengguna memilih 'Ya, Ubah!', tampilkan SweetAlert untuk konfirmasi berhasil
        await Swal.fire({
            title: 'Data Berhasil Diubah',
            text: 'Data Anda telah berhasil diperbarui.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });

        // Kirim data form menggunakan AJAX
        const formData = new FormData(document.querySelector('form'));
        fetch('profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Reload halaman setelah sukses
            setTimeout(() => {
                location.reload();
            }, 100);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

// Menambahkan event listener untuk konfirmasi ketika meninggalkan halaman
window.addEventListener('beforeunload', function (event) {
    if (formChanged) {
        // Munculkan pesan konfirmasi jika ada perubahan
        const message = "Anda telah mengubah data, tetapi belum menyimpan perubahan. Apakah Anda yakin ingin meninggalkan halaman?";
        event.returnValue = message; // Untuk sebagian besar browser
        return message; // Untuk beberapa browser lain yang lebih lama
    }
});

// Menambahkan konfirmasi logout
function confirmLogout() {
    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda akan keluar dari akun ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php'; 
        }
    });
}