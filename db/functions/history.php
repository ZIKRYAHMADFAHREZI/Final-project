<?php
session_start();
// Koneksi database
require 'db/connection.php';

class ReservationSystem {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function isUserLoggedIn() {
        return isset($_SESSION['id_user']);
    }

    public function redirectIfNotLoggedIn($redirectUrl = 'login.php') {
        if (!$this->isUserLoggedIn()) {
            header('Location: ' . $redirectUrl);
            exit();
        }
    }

    public function getUserReservations($id_user) {
        try {
            $query = "
                SELECT 
                    r.*, 
                    ro.number_room, 
                    t.name_type 
                FROM reservations r
                JOIN rooms ro ON r.id_room = ro.id_room
                JOIN types t ON ro.id_type = t.id_type
                WHERE r.id_user = :id_user
                ORDER BY r.reservation_date DESC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching reservations: " . $e->getMessage());
        }
    }
}
// Inisialisasi sistem reservasi
$reservationSystem = new ReservationSystem($pdo);

// Periksa apakah pengguna sudah login
$reservationSystem->redirectIfNotLoggedIn();

// Ambil ID user dari sesi
$id_user = $_SESSION['id_user'];

// Ambil data reservasi
try {
    $reservations = $reservationSystem->getUserReservations($id_user);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>
