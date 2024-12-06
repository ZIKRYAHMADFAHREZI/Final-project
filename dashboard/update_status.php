<?php
require '../db/connection.php';

// Ambil data dari parameter GET
$id_room = isset($_GET['id_room']) ? (int) $_GET['id_room'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;

// Validasi status yang diterima (hanya boleh 'available', 'unavailable', 'pending')
$valid_statuses = ['available', 'unavailable', 'pending'];

if ($id_room && in_array($status, $valid_statuses)) {
    // Siapkan query untuk memperbarui status kamar
    $stmt = $pdo->prepare("UPDATE rooms SET status = :status WHERE id_room = :id_room");
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':id_room', $id_room, PDO::PARAM_INT);

    // Eksekusi query dan cek hasilnya
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Status tidak valid atau ID kamar tidak ditemukan']);
}
?>
