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
                // Ambil informasi reservasi, termasuk jenis durasi dari kolom hour dan tanggal to_date
                $query = "SELECT hour, to_date FROM reservations WHERE id_reservation = :id_reservation";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->execute();
                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    throw new Exception("Reservasi tidak ditemukan.");
                }

                // Ambil nilai durasi dari kolom hour
                $hour_enum = $reservation['hour'];
                $to_date = $reservation['to_date']; // Kolom to_date untuk tanggal

                // Tentukan durasi berdasarkan hour enum
                if ($hour_enum === '3 jam') {
                    $duration_hours = 3;
                    $check_in_date = new DateTime();
                    $check_out_date = $check_in_date->add(new DateInterval("PT{$duration_hours}H"));
                } elseif ($hour_enum === '12 jam') {
                    $duration_hours = 12;
                    $check_in_date = new DateTime();
                    $check_out_date = $check_in_date->add(new DateInterval("PT{$duration_hours}H"));
                } elseif ($hour_enum === '24 jam') {
                    if (empty($to_date)) {
                        throw new Exception("Kolom to_date tidak boleh kosong untuk durasi 24 jam.");
                    }
                    $check_in_date = new DateTime();
                    $to_date_object = new DateTime($to_date);
                    $check_out_date = $to_date_object->setTime(
                        $check_in_date->format('H'),
                        $check_in_date->format('i'),
                        $check_in_date->format('s')
                    );
                } else {
                    throw new Exception("Durasi tidak valid: $hour_enum.");
                }

                // Simpan check_out_date dalam format string
                $formatted_check_out_date = $check_out_date->format('Y-m-d H:i:s');

                // Update reservasi dengan check-in dan check-out date
                $updateReservation = "
                    UPDATE reservations
                    SET status = 'confirmed', payment_status = 'paid', check_in_date = NOW(), check_out_date = :check_out_date
                    WHERE id_reservation = :id_reservation
                ";
                $stmt = $pdo->prepare($updateReservation);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->bindParam(':check_out_date', $formatted_check_out_date, PDO::PARAM_STR);
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

                $status = 'success';
                $message = "Pembayaran dikonfirmasi. Check-out date: $formatted_check_out_date.";
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