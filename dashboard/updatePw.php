<?php
session_start();
require '../db/connection.php';
// Cek apakah pengguna sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    // Jika belum login, arahkan ke halaman login
    header('Location: ../login.php');
    exit;
}

// Cek apakah pengguna memiliki role 'admin'
if ($_SESSION['role'] !== 'admin') {
    // Jika bukan admin, arahkan ke halaman lain (misalnya halaman beranda atau halaman akses terbatas)
    header('Location: ../index.php');
    exit;
}
$id_user = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $password_lama = htmlspecialchars($_POST['password_lama']);
        $password_baru = htmlspecialchars($_POST['password_baru']);
        $konfirmasi_password = htmlspecialchars($_POST['konfirmasi_password']);

        // Validasi input
        if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
            $message = "<script>Swal.fire('Error', 'Semua field harus diisi!', 'error');</script>";
        } elseif ($password_baru !== $konfirmasi_password) {
            $message = "<script>Swal.fire('Error', 'Password baru dan konfirmasi tidak cocok!', 'error');</script>";
        } else {
            // Periksa password lama
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id_user = :id_user");
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password_lama, $user['password'])) {
                $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
                $update_stmt = $pdo->prepare("UPDATE users SET password = :password_baru WHERE id_user = :id_user");
                $update_stmt->bindParam(':password_baru', $hashed_password);
                $update_stmt->bindParam(':id_user', $id_user);
                $update_stmt->execute();

                $message = "<script>Swal.fire('Berhasil', 'Password berhasil diubah!', 'success');</script>";
            } else {
                $message = "<script>Swal.fire('Error', 'Password lama salah!', 'error');</script>";
            }
        }
    } catch (PDOException $e) {
        $message = "<script>Swal.fire('Error', 'Terjadi kesalahan pada server!', 'error');</script>";
        error_log("Database Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganti Password</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" 
    rel="stylesheet"
/>
<link rel="stylesheet" href="../css/admin.css">
<style>
.toggle-btn {
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: #343a40;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px;
    cursor: pointer;
    z-index: 1000;
    transition: left 0.3s ease-in-out;
}
.toggle-btn.closed {
    left: 15px;
}
</style>
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
                <li><a href="delete_rooms.php" class="dropdown-item">Hapus kamar</a></li>
                <li><a href="rooms.php" class="dropdown-item">Status kamar</a></li>
                <li><a href="add_rooms.php" class="dropdown-item">Tambah Kamar</a></li>
            </ul>
        </li>
        <li><a href="payments.php"><i class="fa fa-credit-card me-2"></i> Pembayaran</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content " id="content">
    <header>
        <h1 class="text-center">Ganti Password</h1>
    </header>
    <div class="container">
        <form id="updateForm" action="" method="post" enctype="multipart/form-data">
        <div class="mb-3 position-relative">
            <input 
                type="password" 
                class="form-control" 
                id="password_lama" 
                name="password_lama"
                placeholder="Password Lama" 
                required
            >
            <i class="fa fa-eye toggle-password" id="togglePassword_lama" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>
        <div class="mb-3 position-relative">
            <input 
                type="password" 
                class="form-control" 
                id="password_baru" 
                name="password_baru" 
                placeholder="Password Baru"
                required
            >
            <i class="fa fa-eye toggle-password" id="togglePassword_baru" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>
        <div class="mb-3 position-relative">
            <input 
                type="password" 
                class="form-control" 
                id="konfirmasi_password" 
                name="konfirmasi_password"
                placeholder="Konfirmasi Password Baru"
                required
            >
            <i class="fa fa-eye toggle-password" id="togglePassword_konfirmasi" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
        </div>
            <div class="d-flex justify-content-between">
                <button type="reset" class="btn btn-secondary">Cancel</button>
                <button type="button" id="submitButton" class="btn btn-primary">Ubah Data!</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        // Menambahkan event listener untuk setiap ikon mata
        document.getElementById('togglePassword_lama').addEventListener('click', function () {
        const passwordField = document.getElementById('password_lama');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePassword_baru').addEventListener('click', function () {
        const passwordField = document.getElementById('password_baru');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePassword_konfirmasi').addEventListener('click', function () {
        const passwordField = document.getElementById('konfirmasi_password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });
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

// Menampilkan pesan berdasarkan kondisi PHP
<?php if (isset($message)) { echo $message; } ?>
</script>
<script src="../js/admin.js"></script>
</body>
</html>
