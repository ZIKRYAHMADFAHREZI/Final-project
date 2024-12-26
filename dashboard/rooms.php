<?php
session_start();
require '../db/connection.php';

class RoomManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRoomsGroupedByType() {
        $stmt = $this->pdo->query("
            SELECT 
                t.id_type,
                t.name_type,
                r.id_room,
                r.number_room,
                r.status
            FROM types t 
            LEFT JOIN rooms r ON t.id_type = r.id_type 
            ORDER BY t.id_type, r.number_room
        ");

        $rooms_by_type = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($rooms_by_type[$row['name_type']])) {
                $rooms_by_type[$row['name_type']] = [];
            }
            if ($row['id_room']) {  // Only add if room exists
                $rooms_by_type[$row['name_type']][] = [
                    'id_room' => $row['id_room'],
                    'number_room' => $row['number_room'],
                    'status' => $row['status']
                ];
            }
        }
        return $rooms_by_type;
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

$roomManager = new RoomManager($pdo);
$rooms_by_type = $roomManager->getRoomsGroupedByType();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Status Kamar</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/togle.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" 
    rel="stylesheet"
/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Previous head content remains the same -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .room-list {
        display: block;
        text-align: left;
        margin-bottom: 30px;
    }
    .room {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 10px 20px;
        border-radius: 5px;
        text-align: center;
        width: 80px;
        height: 80px;
        display: inline-block;
        margin: 5px;
        font-size: 18px;
        color: #333;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .room:hover {
        transform: translateY(-3px);
    }
    .available {
        background-color: green;
        color: white;
    }
    .unavailable {
        background-color: red;
        color: white;
    }
    .pending {
        background-color: yellow;
        color: black;
    }
    .number {
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 16px;
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
        <h1 class="text-center mb-5">Status Kamar</h1>
    </header>

    <?php foreach ($rooms_by_type as $type => $rooms): ?>
        <div class="room-category mb-4">
            <h2 class="text-left mb-3"><?php echo htmlspecialchars($type); ?></h2>
            <div class="room-list">
                <?php foreach ($rooms as $room): ?>
                    <div class="room <?= htmlspecialchars($room['status']) ?>" data-room-id="<?= $room['id_room'] ?>" onclick="changeStatus(<?= $room['id_room'] ?>, '<?= $room['status'] ?>')">
                        <p class='number'><?= htmlspecialchars($room['number_room']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Include the JavaScript function -->
<script>
    function changeStatus(roomId, currentStatus) {
        // Tentukan status baru berdasarkan status saat ini
        let newStatus;
        let statusText;
        let colorClass;

        if (currentStatus === 'available') {
            newStatus = 'unavailable';
            statusText = 'Tidak Tersedia';
            colorClass = 'unavailable';
        } else if (currentStatus === 'unavailable') {
            newStatus = 'available';
            statusText = 'Tersedia';
            colorClass = 'available';
        } else if (currentStatus === 'pending') {
            newStatus = 'available';
            statusText = 'Tersedia';
            colorClass = 'available';
        } else {
            // Jika statusnya tidak valid, keluar dari fungsi
            Swal.fire('Gagal', 'Status tidak valid', 'error');
            return;
        }

        // Konfirmasi dengan pengguna apakah ingin mengubah status
        Swal.fire({
            title: `Ubah status ke ${statusText}?`,
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal',
            icon: 'question',
        }).then((result) => {
            if (result.isConfirmed) {
                // Mengupdate status kamar di database melalui AJAX
                fetch(`update_status.php?id_room=${roomId}&status=${newStatus}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Perbarui status secara visual di halaman
                            const roomElement = document.querySelector(`.room[data-room-id='${roomId}']`);
                            roomElement.classList.remove('available', 'unavailable', 'pending');
                            roomElement.classList.add(colorClass);

                            // Perbarui atribut data-status agar status bisa diubah kembali
                            roomElement.setAttribute('onclick', `changeStatus(${roomId}, '${newStatus}')`);
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat memperbarui status.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui status.', 'error');
                    });
            }
        });
    }
</script>
<script src="../js/admin.js"></script>
</body>
</html>
