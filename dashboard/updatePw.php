<?php
require '../db/connection.php'; // Pastikan $pdo sudah terinisialisasi di file ini

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $password_lama = htmlspecialchars($_POST['password_lama']);
        $password_baru = htmlspecialchars($_POST['password_baru']);
        $konfirmasi_password = htmlspecialchars($_POST['konfirmasi_password']);

        // Validasi input
        if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
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
            $update_stmt = $pdo->prepare("UPDATE users SET password = :password_baru WHERE email = :email");
            $update_stmt->bindParam(':password_baru', $hashed_password);
            $update_stmt->bindParam(':email', $_POST['email']);
            $update_stmt->execute();

            if ($update_stmt->rowCount() > 0) {
                echo "<script>Swal.fire('Berhasil', 'Password berhasil diubah!', 'success');</script>";
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
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
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
                <li><a href="update_type.php" class="dropdown-item">Update Tipe</a></li>
            </ul>
        </li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>


<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content" id="content">
    <header>
        <h1 class="text-center">Ganti Email & Password</h1>
    </header>
    <div class="form-container">
        <form id="updateForm" action="" method="post" enctype="multipart/form-data">
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
</script>
<script src="../js/admin.js"></script>
</body>
</html>