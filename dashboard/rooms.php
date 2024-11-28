<?php 
require '../db/connection.php';

// Ambil data tipe kamar
$typeQuery = "SELECT * FROM types";
$typeResult = $pdo->query($typeQuery);
$roomTypes = [];

if ($typeResult->rowCount() > 0) {
    while ($row = $typeResult->fetch(PDO::FETCH_ASSOC)) {
        $roomTypes[$row['id_type']] = $row['type'];
    }
}

// Ambil data kamar dan urutkan berdasarkan tipe dan nomor
$roomQuery = "SELECT * FROM rooms ORDER BY id_type, number_room ASC";
$roomResult = $pdo->query($roomQuery);

// Array untuk menyimpan kamar berdasarkan tipe
$roomsByType = [];

if ($roomResult->rowCount() > 0) {
    while ($row = $roomResult->fetch(PDO::FETCH_ASSOC)) {
        $roomsByType[$row['id_type']][] = $row;
    }
}

// Ambil ID kamar
$roomId = intval($_GET['id_room']);

// Ambil status kamar saat ini
$query = "SELECT status FROM rooms WHERE id_room = :id_room";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_room', $roomId, PDO::PARAM_INT);
$stmt->execute();
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if ($room) {
    switch ($room['status']) {
        case 'available':
            $newStatus = 'unavailable';
            break;
        case 'unavailable':
            $newStatus = 'pending';
            break;
        case 'pending':
            $newStatus = 'available';
            break;
        default:
            $newStatus = 'available';
    }

    $updateQuery = "UPDATE rooms SET status = :newStatus WHERE id_room = :id_room";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':newStatus', $newStatus);
    $updateStmt->bindParam(':id_room', $roomId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo json_encode(["success" => true, "newStatus" => $newStatus]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal memperbarui status"]);
    }
} 

$pdo = null; // Tutup koneksi
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cek Kamar</title>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link rel="stylesheet" href="../css/admin.css">
<style>
    .room {
        display: inline-block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        margin: 5px;
        text-align: center;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }
    .room.available {
        background-color: green;
    }
    .room.unavailable {
        background-color: red;
    }
    .room.pending {
        background-color: yellow; /* Kamar dalam status pending berwarna kuning */
    }
</style>
<script>
    function toggleRoomStatus(roomId) {
        const isConfirmed = confirm("Yakin?");
        if (isConfirmed) {
            fetch(`updateRoomStatus.php?id_room=${roomId}`, { method: "GET"
                            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const roomElement = document.getElementById(`room-${roomId}`);
                        roomElement.classList.remove("available", "unavailable", "pending"); // Hapus semua kelas
                        roomElement.classList.add(data.newStatus); // Tambahkan kelas baru berdasarkan status
                    } else {
                        alert("Gagal mengubah status kamar!");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat mengubah status kamar!");
                });
        }
    }
</script>
</head>
<body>
<div class="sidebar">
    <div class="user-panel text-center mb-4">
        <img src="../img/person.svg" alt="admin" width="20%">
        <p class="mt-2"><i class="fa fa-circle text-success"></i> logged in</p>
    </div>
    <ul class="list-unstyled">
        <li><a href="index.php"><i class="fa fa-home me-2"></i> Beranda</a></li>
        <li><a href="rooms.php"><i class="fa fa-lock me-2"></i> Cek Kamar</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>
<div class="container mt-5">
    <h1 class="mb-4">Cek Kamar</h1>
    <?php foreach ($roomTypes as $typeId => $typeName): ?>
        <div>
            <h3><?php echo htmlspecialchars($typeName); ?></h3>
            <div>
                <?php if (!empty($roomsByType[$typeId])): ?>
                    <?php foreach ($roomsByType[$typeId] as $room): ?>
                        <span
                            id="room-<?php echo $room['id_room']; ?>"
                            class="room <?php echo htmlspecialchars($room['status']); ?>"
                            onclick="toggleRoomStatus(<?php echo $room['id_room']; ?>)">
                            <?php echo htmlspecialchars($room['number_room']); ?>
                        </span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada kamar tersedia untuk tipe ini.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- sweet alert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda akan keluar dari akun ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../logout.php'; // Ganti URL sesuai dengan rute logout Anda
        }
    });
}
</script>
</body>
</html>            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const roomElement = document.getElementById(`room-${roomId}`);
                        roomElement.classList.remove("available", "unavailable", "pending"); // Hapus semua kelas
                        roomElement.classList.add(data.newStatus); // Tambahkan kelas baru berdasarkan status
                    } else {
                        alert("Gagal mengubah status kamar!");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat mengubah status kamar!");
                });
        }
    }
</script>
</head>
<body>
<div class="sidebar">
    <div class="user-panel text-center mb-4">
        <img src="../img/person.svg" alt="admin" width="20%">
        <p class="mt-2"><i class="fa fa-circle text-success"></i> logged in</p>
    </div>
    <ul class="list-unstyled">
        <li><a href="index.php"><i class="fa fa-home me-2"></i> Beranda</a></li>
        <li><a href="rooms.php"><i class="fa fa-lock me-2"></i> Cek Kamar</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>
<div class="container mt-5">
    <h1 class="mb-4">Cek Kamar</h1>
    <?php foreach ($roomTypes as $typeId => $typeName): ?>
        <div>
            <h3><?php echo htmlspecialchars($typeName); ?></h3>
            <div>
                <?php if (!empty($roomsByType[$typeId])): ?>
                    <?php foreach ($roomsByType[$typeId] as $room): ?>
                        <span
                            id="room-<?php echo $room['id_room']; ?>"
                            class="room <?php echo htmlspecialchars($room['status']); ?>"
                            onclick="toggleRoomStatus(<?php echo $room['id_room']; ?>)">
                            <?php echo htmlspecialchars($room['number_room']); ?>
                        </span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada kamar tersedia untuk tipe ini.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- sweet alert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda akan keluar dari akun ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../logout.php'; // Ganti URL sesuai dengan rute logout Anda
        }
    });
}
</script>
</body>
</html>