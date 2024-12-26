<?php
session_start();
require 'db/connection.php'; // File koneksi ke database

// Validasi input
if (!isset($_GET['id_reservation']) || !is_numeric($_GET['id_reservation'])) {
    die("ID reservasi tidak valid.");
}
$id_reservation = intval($_GET['id_reservation']);

// Validasi Sesi
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}
$id_user = $_SESSION['id_user'];

// Kelas Invoice
class Invoice {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getReservationDetails($id_reservation, $id_user) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.id_reservation, 
                       r.start_date, 
                       r.to_date, 
                       r.total_amount, 
                       r.payment_proof, 
                       r.status,
                       p.method AS payment_method, 
                       p.account_name, 
                       p.payment_number,
                       rm.number_room, 
                       t.name_type,
                       r.check_in_date, 
                       r.check_out_date
                FROM reservations r
                JOIN pay_methods p ON r.id_pay_method = p.id_pay_method
                JOIN rooms rm ON r.id_room = rm.id_room
                JOIN types t ON rm.id_type = t.id_type
                WHERE r.id_reservation = :id_reservation
                  AND r.id_user = :id_user
            ");

            // Bind parameter
            $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Reservasi tidak ditemukan atau tidak dapat diakses.");
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Terjadi kesalahan saat mengambil data reservasi.");
        }
    }
}

// Inisialisasi Objek Invoice
try {
    $invoice = new Invoice($pdo);
    $reservation = $invoice->getReservationDetails($id_reservation, $id_user);
} catch (Exception $e) {
    die($e->getMessage());
}
?>