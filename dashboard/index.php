<?php
session_start();
require '../db/connection.php';

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Waktu sekarang
$current_time = date('Y-m-d H:i:s');

try {
    // Mulai transaksi
    $pdo->beginTransaction();

    // Ambil data reservasi yang sudah melewati check_out_date
    $query = "
        SELECT id_room 
        FROM reservations 
        WHERE check_out_date <= :current_time AND status = 'Confirmed'
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':current_time', $current_time, PDO::PARAM_STR);
    $stmt->execute();
    $rooms_to_update = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Jika ada kamar yang perlu diperbarui
    if (!empty($rooms_to_update)) {
        // Perbarui status kamar menjadi 'available'
        $updateQuery = "
            UPDATE rooms
            SET status = 'available'
            WHERE id_room = :id_room
        ";
        $updateStmt = $pdo->prepare($updateQuery);

        // Loop melalui semua kamar yang perlu diperbarui
        foreach ($rooms_to_update as $room) {
            $updateStmt->bindParam(':id_room', $room['id_room'], PDO::PARAM_INT);
            $updateStmt->execute();
        }

        // Perbarui status reservasi menjadi 'Completed'
        $updateReservationQuery = "
            UPDATE reservations
            SET status = 'Completed'
            WHERE check_out_date <= :current_time AND status = 'Confirmed'
        ";
        $reservationStmt = $pdo->prepare($updateReservationQuery);
        $reservationStmt->bindParam(':current_time', $current_time, PDO::PARAM_STR);
        $reservationStmt->execute();
    }

    // Commit transaksi
    $pdo->commit();

    // echo "Sistem berhasil memperbarui status kamar.";
} catch (Exception $e) {
    // Rollback jika ada kesalahan
    $pdo->rollBack();
    echo "Terjadi kesalahan: " . $e->getMessage();
}

class ReservationManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAllReservations()
    {
        $query = "
            SELECT r.*, u.username, u.email, rt.number_room, pm.method, rt_type.name_type
            FROM reservations r
            JOIN users u ON r.id_user = u.id_user
            JOIN rooms rt ON r.id_room = rt.id_room
            JOIN pay_methods pm ON r.id_pay_method = pm.id_pay_method
            JOIN types rt_type ON rt.id_type = rt_type.id_type
            ORDER BY r.reservation_date DESC
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomStatistics()
    {
        $query = "
            SELECT COUNT(*) AS rooms, 
                   SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) AS available,
                   SUM(CASE WHEN status = 'unavailable' THEN 1 ELSE 0 END) AS unavailable,
                   SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending 
            FROM rooms
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

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

$reservationManager = new ReservationManager($pdo);

// Ambil data reservasi
$reservations = $reservationManager->fetchAllReservations();

// Ambil statistik kamar
$roomStats = $reservationManager->getRoomStatistics();
$availableRooms = $roomStats['available'] ?? 0;
$unavailableRooms = $roomStats['unavailable'] ?? 0;
$pendingRooms = $roomStats['pending'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/togle.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        rel="stylesheet" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
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
            <h1 class="text-center mb-5">Admin Portal</h1>
        </header>

        <!-- Box Elements -->
        <div class="card-container mb-4 container">
            <div class="card green-bg">
                <p>Total Kamar Tersedia: <?= $availableRooms; ?></p>
            </div>
            <div class="card red-bg">
                <p>Total Kamar Terpakai: <?= $unavailableRooms; ?></p>
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
                    <?php if (count($reservations) >= 0): ?>
                        <?php
                        $no = 1; // Inisialisasi variabel untuk nomor urut
                        foreach ($reservations as $reservation):
                        ?>
                            <tr>
                                <td><?= $no++; ?></td> <!-- Menampilkan nomor urut dan meningkatkan $no -->
                                <td>
                                    <a href="javascript:void(0);" onclick="showDetailUser('<?= htmlspecialchars($reservation['username']); ?>')">
                                        <?= htmlspecialchars($reservation['username']); ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($reservation['name_type']); ?></td>
                                <td><?= htmlspecialchars($reservation['number_room']); ?></td>
                                <td><?= htmlspecialchars(date('d F Y', strtotime($reservation['start_date']))); ?></td>
                                <td><?= $reservation['to_date'] !== null ? htmlspecialchars(date('d F Y', strtotime($reservation['to_date']))) : ''; ?></td>
                                <td><?= htmlspecialchars($reservation['method']); ?></td>
                                <td><?= htmlspecialchars($reservation['total_amount']); ?></td>
                                <td style="color: <?= $reservation['status'] === 'Pending' ? 'orange' : ($reservation['status'] === 'Confirmed' ? 'green' : ($reservation['status'] === 'Completed' ? 'blue' : 'red')); ?>; font-weight: bold;">
                                    <?= htmlspecialchars($reservation['status']); ?>
                                </td>

                                <td style="color: <?= $reservation['payment_status'] === 'Unpaid' ? 'orange' : ($reservation['payment_status'] === 'Paid' ? 'green' : 'red'); ?>; font-weight: bold;">
                                    <?= htmlspecialchars($reservation['payment_status']); ?>
                                </td>
                                <td><a href="javascript:void(0);" onclick="showPaymentProof('<?= htmlspecialchars($reservation['payment_proof']); ?>')">Lihat Bukti Pembayaran</a></td>
                                <td>
                                    <?php
                                    // Ambil status dan payment_status dari database
                                    $reservationId = $reservation['id_reservation'];
                                    $status = $reservation['status'];
                                    $paymentStatus = $reservation['payment_status'];

                                    // Cek apakah status pembayaran memungkinkan tombol tampil
                                    if ($status == 'Cancelled' || $paymentStatus == 'Refunded' || $status == 'Confirmed' || $status == 'Completed') {
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
                debug: false, // Aktifkan debug
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
                    allowEscapeKey: false, // Tidak bisa menutup dengan tombol ESC
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
            }, 500); // Delay 500 ms sebelum menampilkan SweetAlert
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
                    // Fungsi untuk memformat tanggal
                    function formatDate(dateString) {
                        if (!dateString) return 'Tidak tersedia'; // Jika tanggal tidak ada
                        const date = new Date(dateString);
                        const months = [
                            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];
                        const day = date.getDate().toString().padStart(2, '0'); // Hari dengan 2 digit
                        const month = months[date.getMonth()]; // Nama bulan
                        const year = date.getFullYear(); // Tahun
                        return `${day} ${month} ${year}`;
                    }

                    // Isi modal dengan data user
                    document.getElementById('modalUsername').innerText = data.username || 'Tidak tersedia';
                    document.getElementById('modalEmail').innerText = data.email || 'Tidak tersedia';
                    document.getElementById('modalFullName').innerText = `${data.first_name || ''} ${data.last_name || ''}`.trim() || 'Tidak tersedia';
                    document.getElementById('modalPhoneNumber').innerText = data.phone_number || 'Tidak tersedia';
                    document.getElementById('modalDateOfBirth').innerText = formatDate(data.date_of_birth);

                    // Tampilkan modal
                    document.getElementById('userDetailModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Tetap tampilkan modal dengan pesan kesalahan
                    document.getElementById('modalUsername').innerText = 'Tidak tersedia';
                    document.getElementById('modalEmail').innerText = 'Tidak tersedia';
                    document.getElementById('modalFullName').innerText = 'Tidak tersedia';
                    document.getElementById('modalPhoneNumber').innerText = 'Tidak tersedia';
                    document.getElementById('modalDateOfBirth').innerText = 'Tidak tersedia';

                    document.getElementById('userDetailModal').style.display = 'flex';
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