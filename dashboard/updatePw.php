<?php
require '../db/connection.php'; // Pastikan $pdo sudah terinisialisasi di file ini

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email_baru = htmlspecialchars($_POST['email_baru']);
        $password_lama = htmlspecialchars($_POST['password_lama']);
        $password_baru = htmlspecialchars($_POST['password_baru']);
        $konfirmasi_password = htmlspecialchars($_POST['konfirmasi_password']);

        // Validasi input
        if (empty($email_baru) || empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
            echo "<script>Swal.fire('Error', 'Semua field harus diisi!', 'error');</script>";
            exit;
        }

        if ($password_baru !== $konfirmasi_password) {
            echo "<script>Swal.fire('Error', 'Password baru dan konfirmasi tidak cocok!', 'error');</script>";
            exit;
        }

        // Cek password lama
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $_POST['email']); // Email lama yang digunakan untuk login
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password_lama, $user['password'])) {
            // Jika password lama cocok, lakukan update
            $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
            $update_stmt = $pdo->prepare("UPDATE users SET email = :email_baru, password = :password_baru WHERE email = :email_lama");
            $update_stmt->bindParam(':email_baru', $email_baru);
            $update_stmt->bindParam(':password_baru', $hashed_password);
            $update_stmt->bindParam(':email_lama', $_POST['email']);
            $update_stmt->execute();

            if ($update_stmt->rowCount() > 0) {
                echo "<script>Swal.fire('Berhasil', 'Data berhasil diubah!', 'success');</script>";
            } else {
                echo "<script>Swal.fire('Error', 'Tidak ada perubahan pada data!', 'error');</script>";
            }
        } else {
            echo "<script>Swal.fire('Error', 'Password lama salah!', 'error');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Error', 'Terjadi kesalahan pada server!', 'error');</script>";
        error_log("Database Error: " . $e->getMessage()); // Log error untuk debugging
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganti Email & Password</title>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link rel="stylesheet" href="../css/admin.css">
<link rel="icon" type="png" href="img/icon.png">
</head>
<body>
<div class="sidebar">
    <div class="user-panel text-center mb-4">
        <img src="../img/person.svg" alt="admin" width="20%">
        <p class="mt-2"><i class="fa fa-circle text-success"></i> logged in</p>
    </div>
    <ul class="list-unstyled">
        <li><a href="index.php"><i class="fa fa-home me-2"></i> Beranda</a></li>
        <li><a href="rooms.php"><i class="fa fa-lock me-2"></i> Cek Kamar</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>
<div class="content">
    <header>
        <h1>Ganti Email & Password</h1>
    </header>
    <div class="form-container">
        <form id="updateForm" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input 
                    type="email" 
                    class="form-control" 
                    id="email_baru" 
                    name="email_baru"
                    placeholder="Masukkan Email Baru"
                    required
                >
            </div>
            <div class="mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_lama" 
                    name="password_lama"
                    placeholder="Password Lama" 
                    required
                >
            </div>
            <div class="mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_baru" 
                    name="password_baru" 
                    placeholder="Password Baru"
                    required
                >
            </div>
            <div class="mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="konfirmasi_password" 
                    name="konfirmasi_password"
                    placeholder="Konfirmasi Password Baru"
                    required
                >
            </div>
            <div class="d-flex justify-content-between">
                <button type="reset" class="btn btn-secondary">Cancel</button>
                <!-- Tombol ini akan memicu SweetAlert2 -->
                <button type="button" id="submitButton" class="btn btn-primary">Ubah Data!</button>
            </div>
        </form>
    </div>
</div>
<!-- sweet alert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// SweetAlert2 untuk tombol submit
document.getElementById('submitButton').addEventListener('click', function (e) {
    Swal.fire({
        title: 'Konfirmasi Ubah Data',
        text: "Apakah Anda yakin ingin mengubah email dan password?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('updateForm').submit(); // Submit form
        }
    });
});

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
            window.location.href = '../logout.php'; // Ganti URL sesuai dengan rute logout Anda
        }
    });
}
</script>
</body>
</html>