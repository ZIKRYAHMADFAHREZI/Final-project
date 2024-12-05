<?php
session_start();
include 'db/connection.php';

// Validasi login pengguna
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Ambil data pengguna berdasarkan ID yang login
$id_user = $_SESSION['id_user'];
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user");
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data pengguna tidak ditemukan, inisialisasi dengan nilai kosong
    if (!$user) {
        $user = [
            'username' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone_number' => '',
            'date_of_birth' => ''
        ];
    }
} catch (PDOException $e) {
    die("Kesalahan: " . $e->getMessage());
}

// Jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];

    try {
        if (!empty($user['username']) && !empty($user['email'])) {
            // Update data jika username dan email sudah ada
            $stmt = $pdo->prepare("UPDATE users SET 
                username = :username, 
                first_name = :first_name, 
                last_name = :last_name, 
                email = :email, 
                phone_number = :phone_number, 
                date_of_birth = :date_of_birth 
                WHERE id_user = :id_user");
        } else {
            // Tambah data baru jika belum ada
            $stmt = $pdo->prepare("INSERT INTO users 
                (id_user, username, first_name, last_name, email, phone_number, date_of_birth) 
                VALUES (:id_user, :username, :first_name, :last_name, :email, :phone_number, :date_of_birth)");
        }

        // Bind parameter
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->execute();

        // Redirect ke halaman yang sama untuk memuat data terbaru
        header("Location: user.php");
        exit();
    } catch (PDOException $e) {
        die("Kesalahan saat menyimpan data: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="icon website" type="png" href="img/icon.png">
<link rel="stylesheet" href="css/trans.css">
<style>
        body {
            font-family: Arial, sans-serif;
            /* background-color: #f4f4f4; */
            /* margin: 0; */
            /* padding: 20px; */
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #5cb85c;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
<div id="loading" class="loading">
    <div class="spinner"></div>
    <h2 class="loading-text">GRAND MUTIARA</h2>
</div>

<div class="form-container">
    <h1>Profil Pengguna</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="first_name">Nama Depan:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Nama Belakang:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Nomor Telepon:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Tanggal Lahir:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']); ?>" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>


<a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="js/trans.js"></script>
    <!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda akan keluar dari akun ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php'; // Ganti URL sesuai dengan rute logout Anda
        }
    });
}
</script>
</body>
</html>