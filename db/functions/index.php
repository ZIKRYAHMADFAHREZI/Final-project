<?php
session_start();
require 'db/connection.php';

// Periksa apakah pengguna sudah login
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Jika sudah login, arahkan ke dashboard
    header('Location: dashboard/index.php');
    exit;
}
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
class UserSession {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function fetchTypes() {
        try {
            $result = $this->pdo->query("SELECT * FROM types");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Menangkap dan menampilkan error
            return [];
        }
    }

    public function handleRememberToken() {
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE remember_token = :token");
                $stmt->execute(['token' => $token]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }
    }
}

// Contoh penggunaan
$userSession = new UserSession($pdo);
$types = $userSession->fetchTypes();
$userSession->handleRememberToken();
?>
