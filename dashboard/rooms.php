<?php 
require '../db/connection.php';

// Fetch room types
$typeQuery = "SELECT * FROM types";
$typeResult = $pdo->query($typeQuery);
$roomTypes = $typeResult->fetchAll(PDO::FETCH_KEY_PAIR);

// Fetch rooms grouped by type
$roomQuery = "SELECT * FROM rooms ORDER BY id_type, number_room ASC";
$roomResult = $pdo->query($roomQuery);
$roomsByType = [];

while ($row = $roomResult->fetch(PDO::FETCH_ASSOC)) {
    $roomsByType[$row['id_type']][] = $row;
}

// Handle room status update
if (isset($_GET['id_room'])) {
    $roomId = filter_var($_GET['id_room'], FILTER_VALIDATE_INT);
    
    if ($roomId !== false) {
        $statusMap = [
            'available' => 'unavailable',
            'unavailable' => 'pending',
            'pending' => 'available'
        ];
        
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("SELECT status FROM rooms WHERE id_room = ?");
            $stmt->execute([$roomId]);
            $currentStatus = $stmt->fetchColumn();
            
            $newStatus = $statusMap[$currentStatus] ?? 'available';
            
            $updateStmt = $pdo->prepare("UPDATE rooms SET status = ? WHERE id_room = ?");
            $success = $updateStmt->execute([$newStatus, $roomId]);
            
            $pdo->commit();
            echo json_encode(["success" => $success, "newStatus" => $newStatus]);
            exit;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
            echo json_encode(["success" => false, "message" => "Database error"]);
            exit;
        }
    }
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
    <style>
        .room {
            display: inline-block;
            width: 60px;
            height: 60px;
            line-height: 60px;
            margin: 8px;
            text-align: center;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        .room:hover {
            transform: scale(1.1);
        }
        .room.available { background-color: #28a745; }
        .room.unavailable { background-color: #dc3545; }
        .room.pending { background-color: #ffc107; color: #000; }
        
        .status-legend {
            margin: 20px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .status-legend span {
            margin-right: 20px;
            display: inline-flex;
            align-items: center;
        }
        .status-indicator {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
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

    <div class="container mt-5">
        <h1 class="mb-4">Manajemen Kamar</h1>
        
        <div class="status-legend">
            <span><div class="status-indicator available"></div> Tersedia</span>
            <span><div class="status-indicator unavailable"></div> Terisi</span>
            <span><div class="status-indicator pending"></div> Pending</span>
        </div>

        <?php foreach ($roomTypes as $typeId => $typeName): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="m-0"><?= htmlspecialchars($typeName) ?></h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($roomsByType[$typeId])): ?>
                        <?php foreach ($roomsByType[$typeId] as $room): ?>
                            <span id="room-<?= $room['id_room'] ?>" 
                                  class="room <?= htmlspecialchars($room['status']) ?>"
                                  onclick="toggleRoomStatus(<?= $room['id_room'] ?>)">
                                <?= htmlspecialchars($room['number_room']) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Tidak ada kamar tersedia untuk tipe ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function toggleRoomStatus(roomId) {
            try {
                const result = await Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengubah status kamar ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                });

                if (result.isConfirmed) {
                    const response = await fetch(`?id_room=${roomId}`);
                    const data = await response.json();

                    if (data.success) {
                        const roomElement = document.getElementById(`room-${roomId}`);
                        roomElement.classList.remove('available', 'unavailable', 'pending');
                        roomElement.classList.add(data.newStatus);

                        await Swal.fire('Berhasil', 'Status kamar berhasil diperbarui', 'success');
                    } else {
                        throw new Error(data.message || 'Gagal mengubah status kamar');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                await Swal.fire('Error', 'Terjadi kesalahan saat mengubah status kamar', 'error');
            }
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../logout.php';
                }
            });
        }
    </script>
</body>
</html>