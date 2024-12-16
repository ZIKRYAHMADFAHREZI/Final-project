<?php
session_start();
require 'db/connection.php'; // Pastikan $pdo sudah terinisialisasi di file ini

// Cek apakah pengguna sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

$message = ''; // Variabel untuk menyimpan pesan

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
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container" style="margin-top: 70px;">
    <h1 class="text-center">Ganti Password</h1>
    <form id="updateForm" method="post">
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password_lama" name="password_lama" placeholder="Password Lama" required>
            <span class="input-group-text" id="toggle-password-lama"><i class="fas fa-eye"></i></span>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password_baru" name="password_baru" placeholder="Password Baru" required>
            <span class="input-group-text" id="toggle-password-baru"><i class="fas fa-eye"></i></span>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Konfirmasi Password Baru" required>
            <span class="input-group-text" id="toggle-konfirmasi-password"><i class="fas fa-eye"></i></span>
        </div>
        <button type="button" id="submitButton" class="btn btn-primary">Ubah Data!</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
</script>

<?php 
// Jika ada pesan, tampilkan
if (!empty($message)) {
    echo $message;
}
?>
</body>
</html>
