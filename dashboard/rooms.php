<?php
require '../db/connection.php';

// Menetapkan id_type untuk setiap tipe kamar
$room_types = [1 => 'Deluxe Ac', 2 => 'Family Room', 3 => 'Superior Ac', 4 => 'Stadard Ac', 5 => 'Superior Fan', 6 => 'Standar Fan'];
$rooms = [];

foreach ($room_types as $id_type => $type_name) {
    $stmt = $pdo->prepare("SELECT id_room, number_room, status FROM rooms WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $rooms[$id_type] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Kamar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/admin.css">
<link rel="icon" type="png" href="img/icon.png">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .room-list {
        display: block;
        text-align: center;
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
            <li><a href="rooms.php" class="active"><i class="fa fa-bed me-2"></i> Cek Kamar</a></li>
            <li><a href="updatePw.php"><i class="fa fa-key me-2"></i> Ganti Email & Password</a></li>
            <li><a href="#" onclick="confirmLogout()"><i class="fa fa-sign-out me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Toggle Button -->
    <button class="toggle-btn" id="toggle-btn">☰</button>

    <!-- Main Content -->
    <div class="container text-center" id="content">
        <h1 class="mb-4">Manajemen Kamar</h1>

        <?php foreach ($room_types as $id_type => $type_name): ?>
            <div class="room-list">
                <h3><?= htmlspecialchars($type_name) ?></h3>
                <div class="room-container">
                    <?php foreach ($rooms[$id_type] as $room): ?>
                        <div class="room <?= htmlspecialchars($room['status']) ?>" data-room-id="<?= $room['id_room'] ?>" onclick="changeStatus(<?= $room['id_room'] ?>, '<?= $room['status'] ?>')">
                            <p class='number'><?= htmlspecialchars($room['number_room']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
</body>
</html>
