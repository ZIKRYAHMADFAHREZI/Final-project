<?php
session_start();
require '../connection.php';

class PasswordChanger {
    private $pdo;
    private $id_user;

    public function __construct($pdo, $id_user) {
        $this->pdo = $pdo;
        $this->id_user = $id_user;
    }

    public function changePassword($password_lama, $password_baru, $konfirmasi_password) {
        if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
            $_SESSION['swal_message'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Semua field harus diisi!'];
            return false;
        }

        if ($password_baru !== $konfirmasi_password) {
            $_SESSION['swal_message'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Password baru dan konfirmasi tidak cocok!'];
            return false;
        }

        try {
            // Periksa password lama
            $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id_user = :id_user");
            $stmt->bindParam(':id_user', $this->id_user);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password_lama, $user['password'])) {
                $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
                $update_stmt = $this->pdo->prepare("UPDATE users SET password = :password_baru WHERE id_user = :id_user");
                $update_stmt->bindParam(':password_baru', $hashed_password);
                $update_stmt->bindParam(':id_user', $this->id_user);
                $update_stmt->execute();

                $_SESSION['swal_message'] = ['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Password berhasil diubah!'];
                return true;
            } else {
                $_SESSION['swal_message'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Password lama salah!'];
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $_SESSION['swal_message'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Terjadi kesalahan pada server!'];
            return false;
        }
    }
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama = htmlspecialchars($_POST['password_lama']);
    $password_baru = htmlspecialchars($_POST['password_baru']);
    $konfirmasi_password = htmlspecialchars($_POST['konfirmasi_password']);

    // Ganti dengan logika untuk mengubah password
    $passwordChanger = new PasswordChanger($pdo, $id_user);
    $passwordChanger->changePassword($password_lama, $password_baru, $konfirmasi_password);

    // Pengecekan role pengguna
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
        // Jika role adalah 'user', redirect ke halaman u_password.php
        header('Location: ../../u_password.php');
        exit;
    } else {
        // Jika role bukan 'user', redirect ke halaman updatePw.php
        header('Location: ../../dashboard/updatePw.php');
        exit;
    }
}
?>
