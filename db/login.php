<?php
session_start();
require 'connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];

      // Debugging: Cek role pengguna
      error_log("User  role: " . $_SESSION['role']);

      if ($user['role'] === 'admin') {
        header("Location: ../dashboard/index.php");
        exit();
      } elseif ($user['role'] === 'user') {
        header("Location: ../index.php");
        exit();
      }
    } else {
      $error = "Email, atau password salah.";
    }
  } else {
    $error = "Harap isi semua kolom.";
  }
}
?>