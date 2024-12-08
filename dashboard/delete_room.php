<?php
// delete_room.php
require '../db/connection.php';

// Only process if this is an AJAX request for room deletion
if (isset($_GET['id_room'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id_room = ?");
        $result = $stmt->execute([$_GET['id_room']]);

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Kamar berhasil dihapus' : 'Gagal menghapus kamar'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
        ]);
    }
    exit; // Stop execution after handling the AJAX request
}