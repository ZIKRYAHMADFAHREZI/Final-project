<?php
session_start();
require '../db/connection.php';

// Set timezone untuk PHP
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk menambahkan jam ke tanggal tertentu
function addHoursToDate($date, $hours) {
    $dateTime = new DateTime($date);
    $dateTime->modify("+{$hours} hours");
    return $dateTime->format('Y-m-d H:i:s'); 
}

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
                // Ambil tanggal saat ini sebagai check-in date
                $check_in_date = new DateTime();

                // Tentukan durasi check-out berdasarkan logika
                $duration_hours = 12; // Default 12 jam
                // Contoh logika untuk menentukan durasi
                if ($check_in_date->format('H') < 6) { // Sebelum jam 6 pagi
                    $duration_hours = 3;
                } elseif ($check_in_date->format('H') >= 18) { // Setelah jam 6 sore
                    $duration_hours = 24;
                }

                // Hitung check-out date
                $check_out_date = addHoursToDate($check_in_date->format('Y-m-d H:i:s'), $duration_hours);

                // Format check_out_date
                $formatted_checkout_date = $check_out_date;

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
                    WHERE id_room = (SELECT id_room FROM reservations WHERE id_reservation = :id_reservation)
                ";
                $stmt = $pdo->prepare($updateRoom);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->execute();

                // Perbarui status kamar menjadi 'available' jika waktu telah melewati check_out_date
                $updateRoomStatus = "
                    UPDATE rooms r
                    JOIN reservations res ON r.id_room = res.id_room
                    SET r.status = 'available'
                    WHERE res.check_out_date <= NOW() AND r.status = 'unavailable'
                ";
                $stmt = $pdo->prepare($updateRoomStatus);
                $stmt->execute();

                // Commit transaksi
                $pdo->commit();

                $status = 'success';
                $message = "Pembayaran dikonfirmasi. Check-out date: $formatted_checkout_date.";
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
