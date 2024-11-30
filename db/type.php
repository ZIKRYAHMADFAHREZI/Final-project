<?php
require 'connection.php';

$today = new DateTime();
$formattedDate = $today->format('Y-m-d'); // Format tanggal menjadi YYYY-MM-DD

if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);

    // Ambil nomor kamar berdasarkan id_type
    $stmt = $pdo->prepare("SELECT number_room FROM rooms WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $number_room = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ID tertentu yang mendukung transit
    $transit_supported_ids = [4, 6]; // Ganti dengan ID type transit yang Anda tentukan

    if (in_array($id_type, $transit_supported_ids)) {
        // Jika ID termasuk dalam transit, ambil harga transit
        $stmt = $pdo->prepare("SELECT price FROM transits WHERE id_type = ?");
        $stmt->execute([$id_type]);
        $transit_price = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Jika tidak, ambil data durasi menginap 12 jam dan 24 jam
        $stmt = $pdo->prepare("SELECT 12hour, 24hour FROM room_rates WHERE id_type = ?");
        $stmt->execute([$id_type]);
        $room_rate = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>