<?php 
session_start();
require '../db/connection.php';

// Pastikan pengguna sudah login dan id_user tersedia di session
if (!isset($_SESSION['id_user'])) {
    die("Access denied: User not logged in.");
}

$id_user = $_SESSION['id_user'];

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = $_POST['email'];

    // Validasi email
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    try {
        // Query update email berdasarkan id_user
        $sql = "UPDATE users SET email = :email WHERE id_user = :id_user";
        $stmt = $pdo->prepare($sql);

        // Bind parameter
        $stmt->bindParam(':email', $new_email, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Eksekusi query
        if ($stmt->execute()) {
            echo "Email successfully updated.";
        } else {
            echo "Failed to update email.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Pembayaran</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/mail.css">
<link rel="stylesheet" href="../css/togle.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="user-panel text-center mb-4">
        <img src="../img/person.svg" alt="admin" width="20%">
        <p class="mt-2"><i class="fa fa-circle text-success"></i> logged in</p>
    </div>
    <ul class="list-unstyled">
        <li><a href="index.php"><i class="fa fa-home me-2"></i> Beranda</a></li>
        <li>
            <a href="#" data-bs-toggle="collapse" data-bs-target="#dropdownMenu" aria-expanded="false" aria-controls="dropdownMenu">
                <i class="fa fa-list me-2"></i> Kamar <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="collapse list-unstyled ms-3" id="dropdownMenu">
                <li><a href="rooms.php" class="dropdown-item">Status kamar</a></li>
                <li><a href="add_rooms.php" class="dropdown-item">Tambah Kamar</a></li>
                <li><a href="delete_rooms.php" class="dropdown-item">Hapus kamar</a></li>
            </ul>
        </li>
        <li><a href="payments.php"><i class="fa fa-credit-card me-2"></i> Pembayaran</a></li>
        <li><a href="updateMail.php"><i class="fas fa-envelope me-2"></i> Ganti Email</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>
<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>
<!-- Main Content -->
<div class="content" id="content">
    <div class="container">
        <header>
            <h1 class="text-center">Ganti Email</h1>
        </header>
        <form id="updateForm" action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Masukkan email Anda:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <button type="submit" id="submitButton" class="btn btn-primary w-100">Ubah</button>
        </form>
    </div>
</div>
<script src="../js/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('submitButton');
    const updateForm = document.getElementById('updateForm');

    // Pastikan elemen ada
    if (submitButton && updateForm) {
        submitButton.addEventListener('click', async function (event) {
            event.preventDefault();

            // Menampilkan SweetAlert untuk konfirmasi
            const result = await Swal.fire({
                title: 'Konfirmasi Ubah Email',
                text: "Apakah Anda yakin ingin mengubah email?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                // Submit form melalui AJAX untuk menghindari refresh halaman langsung
                const formData = new FormData(updateForm);

                try {
                    const response = await fetch(updateForm.action, {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        // Jika berhasil, tampilkan SweetAlert sukses
                        await Swal.fire({
                            title: 'Email Berhasil Diubah',
                            text: 'Email Anda telah berhasil diperbarui.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        });

                        // Reload halaman
                        window.location.reload();
                    } else {
                        // Jika gagal, tampilkan SweetAlert error
                        Swal.fire({
                            title: 'Gagal Mengubah Email',
                            text: 'Terjadi kesalahan saat memperbarui email. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    }
                } catch (error) {
                    // Tangani error lain (misalnya, kesalahan jaringan)
                    Swal.fire({
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan tak terduga. Silakan coba lagi.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                    console.error('Error:', error);
                }
            }
        });
    } else {
        console.error("Submit button or update form not found!");
    }
});

</script>

</body>
</html>