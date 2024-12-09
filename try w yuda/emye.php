<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "db_hotel";

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT long_description FROM types";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Pisahkan teks berdasarkan baris baru dan tambahkan <p>
        $paragraphs = explode("\n", $row['long_description']);
        foreach ($paragraphs as $paragraph) {
            echo "<p>" . htmlspecialchars(trim($paragraph)) . "</p>";
        }
    }
} else {
    echo "Tidak ada data.";
}

// Tutup koneksi
$conn->close();
?>
