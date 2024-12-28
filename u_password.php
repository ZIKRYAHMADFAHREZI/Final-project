<?php
session_start();
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Periksa apakah pengguna memiliki peran 'user'
if ($_SESSION['role'] !== 'user') {
    header('Location: dashboard/index.php');
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
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- bootsrap  -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container" style="margin-top: 70px;">
    <h1 class="text-center">Ganti Password</h1>
    <form action="db/functions/password.php" id="updateForm" method="post">
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password_lama" name="password_lama" placeholder="Password Lama" required>
            <span class="input-group-text" id="toggle-password-lama"><i class="fas fa-eye"></i></span>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="password_baru" name="password_baru" placeholder="Password Baru" minlength="8" maxlength="254" required>
            <span class="input-group-text" id="toggle-password-baru"><i class="fas fa-eye"></i></span>
        </div>
        <div class="mb-3 input-group">
            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Konfirmasi Password Baru" minlength="8" maxlength="254" required>
            <span class="input-group-text" id="toggle-konfirmasi-password"><i class="fas fa-eye"></i></span>
        </div>
        <button type="button" id="submitButton" class="btn btn-primary">Ubah Data!</button>
    </form>
</div>

<script src="js/password.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0"></script>
<!-- bootrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- sweet alert -->
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
