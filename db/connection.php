<?php 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "hotel_db"; 

// Membuat koneksi 
$conn = mysqli_connect($servername, $username, $password, $dbname); 

// Periksa Koneksi 
// if (!$conn) { 
//     die("Koneksi gagal: " . mysqli_connect_error()); 
// } 

// echo "Koneksi berhasil"; 

?> 