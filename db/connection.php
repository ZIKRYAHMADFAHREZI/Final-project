<?php 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "hotel_db"; 


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
  }
// Membuat koneksi 
// $conn = mysqli_connect($servername, $username, $password, $dbname); 

// Periksa Koneksi 
// if (!$conn) { 
//     die("Koneksi gagal: " . mysqli_connect_error()); 
// } 

// echo "Koneksi berhasil"; 

?> 