<?php
require '../db/connection.php';

$tipe_id = $_POST['tipe_id'];

// Get room rates
$sql_room = "SELECT id_room_rate, `12hour`, `24hour` 
             FROM room_rates 
             WHERE id_type = ? 
             LIMIT 1";
$stmt_room = $pdo->prepare($sql_room);
$stmt_room->execute([$tipe_id]);
$room_rate = $stmt_room->fetch(PDO::FETCH_ASSOC);

// Get transit rates
$sql_transit = "SELECT id_transit, price 
                FROM transits 
                WHERE id_type = ? 
                LIMIT 1";
$stmt_transit = $pdo->prepare($sql_transit);
$stmt_transit->execute([$tipe_id]);
$transit = $stmt_transit->fetch(PDO::FETCH_ASSOC);

// Generate options
echo "<option value='' disabled selected>Pilih Harga</option>";

if ($room_rate) {
    echo "<option value='12_{$room_rate['id_room_rate']}'>12 Jam - Rp {$room_rate['12hour']}</option>";
    echo "<option value='24_{$room_rate['id_room_rate']}'>24 Jam - Rp {$room_rate['24hour']}</option>";
}

if ($transit) {
    echo "<option value='transit_{$transit['id_transit']}'>Transit - Rp {$transit['price']}</option>";
}
?>