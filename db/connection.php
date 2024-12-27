<?php

class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $pdo;

    // Constructor untuk inisialisasi parameter database
    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    // Metode untuk membuat koneksi PDO
    public function connect() {
        try {
            $this->pdo = new PDO("mysql:host={$this->servername};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    // Metode untuk menutup koneksi (opsional karena PDO otomatis menutup koneksi saat script selesai)
    public function disconnect() {
        $this->pdo = null;
    }
}

// Contoh penggunaan
$database = new Database("localhost", "root", "", "hotel_db");
$pdo = $database->connect();

// if ($pdo) {
//     echo "Koneksi berhasil";
// }

?>
