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

// Ambil data reservasi dari database
$datas = [];
$query = "SELECT * FROM reservations"; // Ambil semua data dari tabel reservations
$stmt = $pdo->query($query);
$datas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query ke dalam array $datas

// Ambil data reservasi beserta nama pengguna berdasarkan id_user
$query = "
    SELECT r.*, u.username, u.email, rt.number_room, pm.method, rt_type.name_type
    FROM reservations r
    JOIN users u ON r.id_user = u.id_user
    JOIN rooms rt ON r.id_room = rt.id_room
    JOIN pay_methods pm ON r.id_pay_method = pm.id_pay_method
    JOIN types rt_type ON rt.id_type = rt_type.id_type"; // Menambahkan join dengan tabel room_types untuk mengambil name_type
$stmt = $pdo->query($query);
$datas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query ke dalam array $datas


// Menangani form pencarian
if (isset($_POST['cari']) && isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $datas = cari($keyword); // Menampilkan hasil pencarian
}

// Fungsi untuk mencari data berdasarkan keyword
function cari($keyword) {
    global $pdo;
    $sql = "
    SELECT r.*, u.username, u.email, rt.number_room, pm.method, rt_type.name_type
    FROM reservations r
    JOIN users u ON r.id_user = u.id_user
    JOIN rooms rt ON r.id_room = rt.id_room
    JOIN pay_methods pm ON r.id_pay_method = pm.id_pay_method
    JOIN types rt_type ON rt.id_type = rt_type.id_type
    WHERE
        u.username LIKE :keyword OR
        u.email LIKE :keyword OR
        rt.number_room LIKE :keyword OR
        rt_type.name_type LIKE :keyword OR
        pm.method LIKE :keyword OR
        r.total_amount LIKE :keyword OR 
        r.payment_proof LIKE :keyword";
    
    $stmt = $pdo->prepare($sql);
    $keyword = "%" . $keyword . "%"; // Membungkus keyword dengan wildcard untuk pencarian
    $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan hasil pencarian
}

// Menangani form pencarian
if (isset($_POST['cari']) && isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $datas = cari($keyword); // Menampilkan hasil pencarian
}

// Menghitung jumlah kamar
$queryRoom = "SELECT COUNT(*) AS rooms, 
                     SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) AS available,
                     SUM(CASE WHEN status = 'unavailable' THEN 1 ELSE 0 END) AS unavailable,
                     SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending 
              FROM rooms"; // Pastikan ada tabel rooms dengan status kamar
$stmtRoom = $pdo->query($queryRoom);
$roomStats = $stmtRoom->fetch(PDO::FETCH_ASSOC);

$availableRooms = $roomStats['available'];
$unvailableRooms = $roomStats['unavailable'];
$pendingRooms = $roomStats['pending'];
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
        max-width: 20%;
        background-color: #f8f9fa;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        padding: 20px;
        font-size: 20px;
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
            <p>Total Kamar Tersedia: <?= $availableRooms; ?></p>
        </div>
        <div class="card red-bg">
            <p>Total Kamar Terpakai: <?= $unvailableRooms; ?></p>
        </div>
        <div class="card yellow-bg">
            <p>Total Kamar Terpending: <?= $pendingRooms; ?></p>
        </div>
    </div>

    <!-- Cari -->
    <div class="form-container text-center mt-5">
        <form action="" method="post" class="d-inline-block">
            <input 
                type="text" 
                name="keyword" 
                size="150%" 
                placeholder="Masukkan keyword pencarian" 
                autocomplete="off"
                class="form-control d-inline-block w-50 mb-2"
            >
            <button type="submit" name="cari" class="btn btn-primary">Cari!</button>
        </form>
    </div>

    <!-- Tampilkan data reservations -->
    <div class="mt-5">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Tipe Kamar</th>
                <th>Nomor Kamar</th>
                <th>Check-in Date</th>
                <th>Metode Pembayaran</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Status Pembayaran</th>
                <th>Bukti Pembayaran</th>
                <th>Validasi Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; // Inisialisasi variabel untuk nomor urut
            foreach ($datas as $data): 
            ?>
                <tr>
                    <td><?= $no++; ?></td> <!-- Menampilkan nomor urut dan meningkatkan $no -->
                    <td><?= htmlspecialchars($data['username']); ?></td>
                    <td><?= htmlspecialchars($data['name_type']); ?></td>
                    <td><?= htmlspecialchars($data['number_room']); ?></td>
                    <td><?= htmlspecialchars($data['start_date']); ?></td>
                    <td><?= htmlspecialchars($data['method']); ?></td>
                    <td><?= htmlspecialchars($data['total_amount']); ?></td>
                    <td><?= htmlspecialchars($data['status']); ?></td>
                    <td><?= htmlspecialchars($data['payment_status']); ?></td>
                    <td><a href="javascript:void(0);" onclick="showPaymentProof('<?= htmlspecialchars($data['payment_proof']); ?>')">Lihat Bukti Pembayaran</a></td>
                    <td>
                        <?php
                        // Ambil status dan payment_status dari database untuk memeriksa apakah tombol harus disembunyikan
                        $reservationId = $data['id_reservation'];
                        $status = $data['status'];
                        $paymentStatus = $data['payment_status'];

                        // Cek apakah sudah dilakukan konfirmasi atau refund
                        if ($status == 'confirmed' || $paymentStatus == 'paid' || $status == 'cancelled' || $paymentStatus == 'refunded') {
                            // Jika sudah, sembunyikan tombol
                            echo '<span class="text-muted">Selesai</span>';
                        } else {
                            // Jika belum, tampilkan tombol aksi
                            echo '<a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="showActionDialog(' . $reservationId . ')">Konfirmasi</a>';
                        }
                        ?>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</div>
<div id="paymentModal" style="display:none;">
    <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px;">
            <img id="paymentImage" src="" alt="Bukti Pembayaran" style="max-width: 600px; max-height: 400px; width: auto; height: auto;">
            <button onclick="closeModal()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<script>
function showActionDialog(reservationId) {
    // Menambahkan penundaan untuk memperlambat eksekusi
    setTimeout(() => {
        Swal.fire({
            title: 'Pilih Aksi',
            text: 'Pilih apakah ingin mengonfirmasi pembayaran atau refund.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Konfirmasi Pembayaran',
            cancelButtonText: 'Refund',
            allowOutsideClick: false, // Tidak bisa menutup dengan klik di luar
            allowEscapeKey: false,    // Tidak bisa menutup dengan tombol ESC
        }).then((result) => {
            // Memastikan pengguna memilih salah satu opsi sebelum melanjutkan
            if (result.isConfirmed) {
                // Jika Konfirmasi Pembayaran
                window.location.href = 'confirm_payment.php?id=' + reservationId + '&action=confirm';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Jika Refund
                window.location.href = 'confirm_payment.php?id=' + reservationId + '&action=refund';
            }
        });
    }, 500);  // Delay 500 ms sebelum menampilkan SweetAlert
}

function showPaymentProof(fileName) {
    var modal = document.getElementById('paymentModal');
    var img = document.getElementById('paymentImage');
    img.src = '../paynt/uploads/' + fileName;
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('paymentModal').style.display = 'none';
}
</script>
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/admin.js"></script>
</body>
</html>
