<?php
session_start();
require '../db/connection.php';

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

        // Mulai transaksi untuk update data secara bersamaan
        try {
            $pdo->beginTransaction();

            if ($action == 'confirm') {
                // Konfirmasi pembayaran
                $updateReservation = "
                    UPDATE reservations
                    SET status = 'confirmed', payment_status = 'paid', check_in_date = NOW()
                    WHERE id_reservation = :id_reservation
                ";
                $stmt = $pdo->prepare($updateReservation);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
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

                // Commit transaksi
                $pdo->commit();

                // Menyimpan status sukses
                $status = 'success';
                $message = 'Pembayaran dikonfirmasi dan kamar diperbarui.';
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

                // Menyimpan status sukses
                $status = 'success';
                $message = 'Pembayaran dibatalkan dan kamar tersedia kembali.';
            }
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            $pdo->rollBack();
            // Menyimpan status error
            $status = 'error';
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
} else {
    // Jika ID tidak ditemukan
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
// Menampilkan SweetAlert berdasarkan status
if (isset($status)) {
    echo "<script>
        Swal.fire({
            title: '" . ucfirst($status) . "!',
            text: '" . $message . "',
            icon: '" . $status . "',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'index.php';  // Ganti dengan halaman yang sesuai
        });
    </script>";
}
?>

</body>
</html>
