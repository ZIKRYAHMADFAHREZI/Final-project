<?php
session_start();
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Periksa apakah pengguna memiliki peran 'admin'
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}
$message = $_SESSION['swal_message'] ?? null;
unset($_SESSION['swal_message']); // Hapus pesan setelah digunakan
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganti Password</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/togle.css">
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
        <li><a href="updateMail.php"><i class="fas fa-envelope me-2"></i> Ganti Email</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content" id="content">
    <header>
        <h1 class="text-center">Ganti Password</h1>
    </header>
    <div class="container">
        <form id="updateForm" action="../db/functions/password.php" method="post" enctype="multipart/form-data">
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password_lama" name="password_lama" placeholder="Password Lama" required>
            <span class="input-group-text" id="toggle-password-lama"><i class="fas fa-eye"></i></span>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password_baru" name="password_baru" placeholder="Password Baru" minlength="8" maxlength="254" required>
            <span class="input-group-text" id="toggle-password-baru"><i class="fas fa-eye"></i></span>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Konfirmasi Password Baru" maxlength="254" minlength="8" required>
            <span class="input-group-text" id="toggle-konfirmasi-password"><i class="fas fa-eye"></i></span>
        </div>
        <div class="d-flex justify-content-between">
            <button type="reset" class="btn btn-secondary">Cancel</button>
            <button type="button" id="submitButton" class="btn btn-primary">Ubah Data!</button>
        </div>
        </form>
    </div>
</div>

<script src="../js/admin.js"></script>

<script src="../js/password.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if ($message): ?>
    <script>
        Swal.fire({
            icon: '<?= $message['icon'] ?>',
            title: '<?= $message['title'] ?>',
            text: '<?= $message['text'] ?>'
        });
    </script>
<?php endif; ?>
</body>
</html>
