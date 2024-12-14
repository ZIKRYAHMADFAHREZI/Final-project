<?php
// index.php or room_management.php
require '../db/connection.php';

// Fetch all room types with their rooms using JOIN
$stmt = $pdo->query("
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hapus Kamar</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Previous head content remains the same -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        cursor: pointer;
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
                <li><a href="update_type.php" class="dropdown-item">Update Tipe</a></li>
            </ul>
        </li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
    </div>

<button class="toggle-btn" id="toggle-btn">☰</button>

<div class="content" id="content">
    <header>
        <h1 class="text-center mb-5">Hapus Kamar</h1>
    </header>

    <?php foreach ($rooms_by_type as $type => $rooms): ?>
        <div class="room-category mb-4">
            <h2 class="text-center mb-3"><?php echo htmlspecialchars($type); ?></h2>
            <div class="room-list">
                <?php foreach ($rooms as $room): ?>
                    <?php
                    $statusClass = '';
                    switch ($room['status']) {
                        case 'available':
                            $statusClass = 'available';
                            break;
                        case 'unavailable':
                            $statusClass = 'unavailable';
                            break;
                        case 'pending':
                            $statusClass = 'pending';
                            break;
                    }
                    ?>
                    <div class="room <?php echo $statusClass; ?>" 
                            data-room-id="<?php echo $room['id_room']; ?>"
                            onclick="deleteRoom(<?php echo $room['id_room']; ?>)">
                        <div class="number">
                            <?php echo htmlspecialchars($room['number_room']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function deleteRoom(roomId) {
    const roomElement = document.querySelector(`.room[data-room-id='${roomId}']`);
    const roomStatus = roomElement.classList.contains('unavailable') ? 'unavailable' : 'available'; // Check status

    if (roomStatus === 'unavailable') {
        Swal.fire({
            title: 'Gagal!',
            text: 'Kamar ini tidak dapat dihapus karena sedang terpakai.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return; // Stop the deletion process
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Kamar ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`delete_room.php?id_room=${roomId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Dihapus!', data.message, 'success');
                        if (roomElement) {
                            roomElement.remove();
                        }
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus kamar.', 'error');
                });
        }
    });
}
</script>


<script src="../js/admin.js"></script>
</body>
</html>