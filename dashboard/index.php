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

$datas = "SELECT * FROM reservations";
function cari($keyword) {
    // Memastikan koneksi PDO
    global $pdo;
    // Query dengan prepared statement untuk mencegah SQL Injection
    $sql = "SELECT * FROM reservations WHERE
                id_user LIKE :keyword OR
                total_price LIKE :keyword OR
                id_type LIKE :keyword OR
                id_rooms LIKE :keyword OR
                id_room_rate LIKE :keyword OR
                id_transit LIKE :keyword OR
                id_payment LIKE :keyword OR
                payment_proof LIKE :keyword";

    // Menyiapkan query
    $stmt = $pdo->prepare($sql);

    // Mengikat parameter :keyword dengan nilai yang dimasukkan
    $keyword = "%" . $keyword . "%";
    $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);

    // Menjalankan query
    $stmt->execute();

    // Mengembalikan hasil pencarian
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Menangani form pencarian
if (isset($_POST['cari']) && isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $results = cari($keyword);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin</title>
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
    .card-container {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .card {
        flex: 1;
        max-width: 30%;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        padding: 15px;
    }
    .card.green-bg {
        background-color: green;
        color: white;
    }
    .card.red-bg {
        background-color: red;
        color: white;
    }
    .card.yellow-bg {
        background-color: yellow;
        color: black;
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
                <li><a href="rooms.php" class="dropdown-item">Status kamar</a></li>
                <li><a href="add_rooms.php" class="dropdown-item">Tambah Kamar</a></li>
                <li><a href="delete_rooms.php" class="dropdown-item">Hapus kamar</a></li>
                <li><a href="update_type.php" class="dropdown-item">Update Tipe</a></li>
            </ul>
        </li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content" id="content">
    <header>
        <h1 class="text-center mb-5">Admin Portal</h1>
    </header>

    <!-- Box Elements -->
    <div class="card-container mb-4">
        <div class="card green-bg">
            <p>Total Kamar Tersedia: <?= $room; ?></p>
        </div>
        <div class="card red-bg">
            <p>Total Kamar Terpakai: <?= $room; ?></p>
        </div>
        <div class="card yellow-bg">
            <p>Total Kamar Terpending: <?= $room; ?></p>
        </div>
    </div>

    <!-- Cari -->
    <div class="form-container text-center mt-5">
        <form action="" method="post" class="d-inline-block">
            <input 
                type="text" 
                name="keyword" 
                size="150%" 
                autofocus 
                placeholder="Masukkan keyword pencarian" 
                autocomplete="off"
                class="form-control d-inline-block w-50 mb-2"
            >
            <button type="submit" name="cari" class="btn btn-primary">Cari!</button>
        </form>
    </div>
    <div>
        <?php $i = 0; ?>
        <?php foreach ($datas as $data): ?>
            <?= htmlspecialchars($data['id_user']); ?>
            <?= htmlspecialchars($data['id_room']); ?>
            <?= htmlspecialchars($data['id_type']); ?>
            <?= htmlspecialchars($data['check_in_date']); ?>
            <?= htmlspecialchars($data['id_pay_method']); ?>
            <?= htmlspecialchars($data['total_ammount']); ?>
            lihat<?= htmlspecialchars($data['payment_proof']); ?>
        <?php endforeach; ?>
        <?php $i++; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/admin.js"></script>
</body>
</html>
