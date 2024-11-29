<?php
require 'connection.php';
$id = $_GET['id'];
$today = new DateTime();
$formattedDate = $today->format('Y-m-d'); // Format tanggal menjadi YYYY-MM-DD

$sql = "SELECT number_room FROM rooms";
$result = $pdo->query($sql);
$number_room = $result->fetchAll(PDO::FETCH_ASSOC); // Ambil semua data kamar
?>