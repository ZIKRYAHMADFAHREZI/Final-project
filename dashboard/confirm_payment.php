<?php
session_start();
require '../db/connection.php';

// Set timezone untuk PHP
date_default_timezone_set('Asia/Jakarta');

// Cek apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Cek apakah id reservation tersedia di URL
if (isset($_GET['id'])) {
    $id_reservation = $_GET['id'];

    // Jika ada aksi yang dipilih (konfirmasi atau refund)
    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        try {
            $pdo->beginTransaction();

            if ($action == 'confirm') {
                // Ambil informasi kamar dan tipe
                $queryRoomInfo = "
                    SELECT r.id_room, r.id_type, rr.12hour, rr.24hour, res.check_in_date
                    FROM reservations res
                    JOIN rooms r ON res.id_room = r.id_room
                    JOIN room_rates rr ON rr.id_type = r.id_type
                    WHERE res.id_reservation = :id_reservation
                ";
                $stmt = $pdo->prepare($queryRoomInfo);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->execute();
                $roomInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$roomInfo) {
                    throw new Exception('Informasi kamar tidak ditemukan.');
                }

                $id_room = $roomInfo['id_room'];
                $id_type = $roomInfo['id_type'];

                // Atur check_in_date dengan timezone
                $check_in_date = new DateTime($roomInfo['check_in_date'], new DateTimeZone('UTC'));
                $check_in_date->setTimezone(new DateTimeZone('Asia/Jakarta'));

                // Logika durasi berdasarkan id_type dan kolom 12hour/24hour
                if (in_array($id_type, [4, 6])) {
                    // Untuk tipe transit, tambahkan 3 jam
                    $check_out_date = clone $check_in_date;
                    $check_out_date->modify('+3 hours');
                } elseif (!empty($roomInfo['12hour'])) {
                    // Untuk tarif 12 jam
                    $check_out_date = clone $check_in_date;
                    $check_out_date->modify('+12 hours');
                } elseif (!empty($roomInfo['24hour'])) {
                    // Untuk tarif 24 jam
                    $check_out_date = clone $check_in_date;
                    $check_out_date->modify('+24 hours');
                } else {
                    throw new Exception('Durasi tidak ditemukan untuk kamar ini.');
                }

                // Debug hasil check-in dan check-out
                echo "Check-in Date (Asia/Jakarta): " . $check_in_date->format('Y-m-d H:i:s') . "<br>";
                echo "Check-out Date (Asia/Jakarta): " . $check_out_date->format('Y-m-d H:i:s') . "<br>";

                // Format check_out_date
                $formatted_checkout_date = $check_out_date->format('Y-m-d H:i:s');

                // Konfirmasi pembayaran dan set check_in_date serta check_out_date
                $updateReservation = "
                    UPDATE reservations
                    SET status = 'confirmed', payment_status = 'paid', check_in_date = NOW(), check_out_date = :check_out_date
                    WHERE id_reservation = :id_reservation
                ";
                $stmt = $pdo->prepare($updateReservation);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->bindParam(':check_out_date', $formatted_checkout_date, PDO::PARAM_STR);
                $stmt->execute();

                // Update status kamar menjadi 'unavailable'
                $updateRoom = "
                    UPDATE rooms
                    SET status = 'unavailable'
                    WHERE id_room = :id_room
                ";
                $stmt = $pdo->prepare($updateRoom);
                $stmt->bindParam(':id_room', $id_room, PDO::PARAM_INT);
                $stmt->execute();

                // Commit transaksi
                $pdo->commit();

                $status = 'success';
                $message = 'Pembayaran dikonfirmasi dan check_out_date diperbarui.';
            } elseif ($action == 'refund') {
                // Refund pembayaran
                $updateReservation = "
                    UPDATE reservations
                    SET status = 'cancelled', payment_status = 'refunded'
                    WHERE id_reservation = :id_reservation
                ";
                $stmt = $pdo->prepare($updateReservation);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->execute();

                // Update status kamar menjadi 'available'
                $updateRoom = "
                    UPDATE rooms
                    SET status = 'available'
                    WHERE id_room = (SELECT id_room FROM reservations WHERE id_reservation = :id_reservation)
                ";
                $stmt = $pdo->prepare($updateRoom);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->execute();

                // Commit transaksi
                $pdo->commit();

                $status = 'success';
                $message = 'Pembayaran dibatalkan dan kamar tersedia kembali.';
            }
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            $pdo->rollBack();
            $status = 'error';
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
} else {
    $status = 'error';
    $message = 'ID Reservasi tidak ditemukan.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran / Refund</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
if (isset($status)) {
    echo "<script>
        Swal.fire({
            title: '" . ucfirst($status) . "!',
            text: '" . $message . "',
            icon: '" . $status . "',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'index.php';
        });
    </script>";
}
?>

</body>
</html>
