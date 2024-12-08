<?php
include 'navbar.php';
require 'db/connection.php';

// Periksa apakah 'id_type' ada dan merupakan angka
if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);
    
    // Menyiapkan query untuk mengambil data berdasarkan id_type
    $stmt = $pdo->prepare("SELECT * FROM types WHERE id_type = :id_type");
    $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
    $stmt->execute();
    
    // Ambil hasilnya
    $type = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan
    if (!$type) {
        die("Jenis tidak ditemukan.");
    }
} else {
    die("ID jenis tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail</title>
<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
</head>
<body>
    <h1>Detail </h1>
    <img height="500px;" src="img/<?= $type['img'] ?>_1.jpg" alt="Logo">
    <div style="display:flex;">
        <img height="100px;" src="img/<?= $type['img'] ?>_2.jpg" alt="Logo">
        <img height="100px;" src="img/<?= $type['img'] ?>_3.jpg" alt="Logo">
        <img height="100px;" src="img/<?= $type['img'] ?>_4.jpg" alt="Logo">
    </div>
    <p>Nama Ruangan: <?= htmlspecialchars($type['name_type']); ?></p>
    <p>Deksripsi Ruangan: <?= htmlspecialchars($type['long_description']); ?></p> <!-- Misalkan ada kolom 'name' di tabel 'types' -->
    
    <!-- Link untuk kembali ke halaman tipe dengan id_type yang sama -->
    <a href="type.php?id_type=<?= $type['id_type']; ?>" class="btn btn-primary">pesan</a>
<!-- Bootstrap JavaScript Libraries -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
></script>
</body>
</html>
