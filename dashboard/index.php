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
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

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
<div class="content" id="content">
    <header>
        <h1 class="text-center mb-5">Admin Portal</h1>
    </header>

    <!-- Box Elements -->
    <div class="card-container mb-4 container">
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

    <!-- Tampilkan data reservations -->
    <div class="mt-5">
        <table id="reservationsTable" class="table table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Tipe Kamar</th>
                <th>Nomor Kamar</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Metode Pembayaran</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Status Pembayaran</th>
                <th>Bukti Pembayaran</th>
                <th>Validasi Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($datas) > 0): ?>
            <?php 
            $no = 1; // Inisialisasi variabel untuk nomor urut
            foreach ($datas as $data): 
            ?>
                <tr>
                    <td><?= $no++; ?></td> <!-- Menampilkan nomor urut dan meningkatkan $no -->
                    <td>
                        <a href="javascript:void(0);" onclick="showDetailUser('<?= htmlspecialchars($data['username']); ?>')">
                            <?= htmlspecialchars($data['username']); ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($data['name_type']); ?></td>
                    <td><?= htmlspecialchars($data['number_room']); ?></td>
                    <td><?= htmlspecialchars(date('d F Y', strtotime($data['start_date']))); ?></td>
                    <td><?= $data['to_date'] !== null ? htmlspecialchars($data['to_date']) : '' ; ?></td>
                    <td><?= htmlspecialchars($data['method']); ?></td>
                    <td><?= htmlspecialchars($data['total_amount']); ?></td>
                    <td><?= htmlspecialchars($data['status']); ?></td>
                    <td><?= htmlspecialchars($data['payment_status']); ?></td>
                    <td><a href="javascript:void(0);" onclick="showPaymentProof('<?= htmlspecialchars($data['payment_proof']); ?>')">Lihat Bukti Pembayaran</a></td>
                    <td>
                        <?php
                        // Ambil status dan payment_status dari database
                        $reservationId = $data['id_reservation'];
                        $status = $data['status'];
                        $paymentStatus = $data['payment_status'];

                        // Cek apakah status pembayaran memungkinkan tombol tampil
                        if ($status == 'cancelled' || $paymentStatus == 'refunded' || $status == 'confirmed') {
                            // Jika status sudah cancelled atau refunded, hanya tampilkan teks
                            echo '<span class="text-muted">Selesai</span>';
                        } else {
                            // Tampilkan tombol aksi meskipun payment_status adalah paid
                            echo '<a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="showActionDialog(' . $reservationId . ')">Konfirmasi</a>';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data pemesanan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>
</div>
<div id="userDetailModal" style="display:none;">
    <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px; width: 90%; max-width: 500px;">
            <h4>Detail User</h4>
            <p><strong>Username:</strong> <span id="modalUsername"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
            <p><strong>Nama Lengkap:</strong> <span id="modalFullName"></span></p>
            <p><strong>Nomor Telepon:</strong> <span id="modalPhoneNumber"></span></p>
            <p><strong>Tanggal Lahir:</strong> <span id="modalDateOfBirth"></span></p>
            <button onclick="closeModal('userDetailModal')" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<div id="paymentModal" style="display:none;">
    <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px;">
            <img id="paymentImage" src="" alt="Bukti Pembayaran" style="max-width: 600px; max-height: 400px; width: auto; height: auto;">
            <button onclick="closeModal('paymentModal')" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- boootsrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/admin.js"></script>
<script>
$(document).ready(function() {
    $('#reservationsTable').DataTable({
        paging: true,
        searching: true,
        info: true,
        ordering: true,
        debug: true, // Aktifkan debug
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});

function showActionDialog(reservationId) {
    // Menambahkan penundaan untuk memperlambat eksekusi
    setTimeout(() => {
        Swal.fire({
            title: 'Pilih Aksi',
            text: 'Pilih apakah ingin mengonfirmasi pembayaran, refund, atau kembali ke menu awal.',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true, // Tambahkan tombol Deny untuk Refund
            confirmButtonText: 'Konfirmasi Pembayaran',
            denyButtonText: 'Refund Pembayaran',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false, // Tidak bisa menutup dengan klik di luar
            allowEscapeKey: false,    // Tidak bisa menutup dengan tombol ESC
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika Konfirmasi Pembayaran
                window.location.href = 'confirm_payment.php?id=' + reservationId + '&action=confirm';
            } else if (result.isDenied) {
                // Jika Refund
                window.location.href = 'confirm_payment.php?id=' + reservationId + '&action=refund';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Jika Cancel, kembali ke menu awal
                window.location.href = 'index.php';
            }
        });
    }, 500);  // Delay 500 ms sebelum menampilkan SweetAlert
}

function showDetailUser(username) {
    fetch(`get_user_detail.php?username=${username}`)
        .then(response => {
            console.log("Status Code:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            // Isi modal dengan data user
            document.getElementById('modalUsername').innerText = data.username;
            document.getElementById('modalEmail').innerText = data.email;
            document.getElementById('modalFullName').innerText = `${data.first_name} ${data.last_name}`;
            document.getElementById('modalPhoneNumber').innerText = data.phone_number;
            document.getElementById('modalDateOfBirth').innerText = data.date_of_birth;

            // Tampilkan modal
            document.getElementById('userDetailModal').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail user: ' + error.message);
        });
}


function showPaymentProof(fileName) {
    var modal = document.getElementById('paymentModal');
    var img = document.getElementById('paymentImage');
    img.src = '../paynt/uploads/' + fileName;
    modal.style.display = 'flex';
}

// Fungsi closeModal dengan parameter modalId
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

</script>
</body>
</html>
