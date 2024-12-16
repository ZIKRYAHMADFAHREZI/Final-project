<?php 
session_start();
require 'db/connection.php';

$id_user = $_SESSION['id_user'];

// Cek apakah pengguna sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil data pengguna berdasarkan ID yang login
try {
    // Ambil data user dan user_profile
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user");
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM user_profile WHERE id_user = :id_user");
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $user_profile = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data pengguna tidak ditemukan, inisialisasi dengan nilai kosong
    if (!$user) {
        $user = [
            'username' => '',
            'first_name' => '',
            'last_name' => '',
            'phone_number' => '',
            'email' => '',
            'date_of_birth' => null, // Pastikan null untuk date_of_birth jika tidak ada
            'password' => '' // Pastikan password tetap kosong
        ];
    }

    // Jika formulir disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $date_of_birth = $_POST['date_of_birth'];

        // Cek apakah email sudah digunakan oleh pengguna lain
        $stmt = $pdo->prepare("SELECT id_user FROM user_profile WHERE email = :email AND id_user != :id_user");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            die("Kesalahan: Email sudah digunakan oleh pengguna lain.");
        }

        // Jika date_of_birth kosong, set ke NULL
        if (empty($date_of_birth)) {
            $date_of_birth = NULL;
        }

        // Jika data pengguna sudah ada (untuk update)
        if ($user_profile) {
            $stmt = $pdo->prepare("UPDATE user_profile SET
                username = :username,
                first_name = :first_name,
                last_name = :last_name,
                phone_number = :phone_number,
                email = :email,
                date_of_birth = :date_of_birth
                WHERE id_user = :id_user");
        } else {
            // Tambah data baru tanpa password
            $stmt = $pdo->prepare("INSERT INTO user_profile 
                (id_profile, id_user, username, first_name, last_name, phone_number, email, date_of_birth) 
                VALUES (NULL, :id_user, :username, :first_name, :last_name, :phone_number, :email, :date_of_birth)");
        }

        // Bind parameter
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date_of_birth', $date_of_birth, PDO::PARAM_STR); // Pastikan bind parameter dengan PDO::PARAM_STR

        // Eksekusi query
        if ($stmt->execute()) {
            echo "Data berhasil disimpan!";
        } else {
            echo "Gagal menyimpan data.";
        }
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        die("Kesalahan: Data yang dimasukkan sudah ada di sistem.");
    } else {
        die("Kesalahan: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="css/trans.css">
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
    }
    .form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 700px;
    margin-top: 75px; /* Menambah jarak atas sesuai kebutuhan */
    margin-left: auto;
    margin-right: auto;
    }
    .form-container h1 {
        font-size: 28px;
        font-weight: bold;
        color: #495057;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
    }
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    .form-group input:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 5px rgba(92, 184, 92, 0.5);
    }
    .form-group button {
        width: 100%;
        padding: 12px;
        border: none;
        color: white;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .logout-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #dc3545;
        font-size: 16px;
        text-decoration: none;
        font-weight: bold;
    }
    .logout-link:hover {
        text-decoration: underline;
    }
    .card-header {
        background-color: #f8f9fa;
        font-size: 20px;
        font-weight: 600;
    }
</style>
</head>
<body>
<?php include 'navbar.php';?>

<div class="form-container">
    <h1>Profil Pengguna</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="first_name">Nama Depan:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user_profile['first_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Nama Belakang:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user_profile['last_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="phone_number">Nomor Telepon:</label>
            <input type="number" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user_profile['phone_number'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="date_of_birth">Tanggal Lahir:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($user_profile['date_of_birth'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <button type="submit" id="submitButton" class="btn btn-success">Simpan</button>
        </div>
        <div class="form-group">
            <button type="reset" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<a href="#" onclick="confirmLogout();" class="logout-link"><i class="fa fa-lock me-2"></i> Logout</a>
<br><br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Menambahkan event listener untuk mendeteksi perubahan pada form
let formChanged = false;

const form = document.querySelector('form');
const formElements = form.querySelectorAll('input, select, textarea');

// Menandai perubahan data pada form
formElements.forEach(element => {
    element.addEventListener('input', () => {
        formChanged = true; // Form telah berubah
    });
});

// Menambahkan event listener pada tombol submit
document.getElementById('submitButton').addEventListener('click', async function (event) {
    event.preventDefault();

    // Menampilkan SweetAlert untuk konfirmasi
    const result = await Swal.fire({
        title: 'Konfirmasi Ubah Data',
        text: "Apakah Anda yakin ingin mengubah data?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        // Jika pengguna memilih 'Ya, Ubah!', tampilkan SweetAlert untuk konfirmasi berhasil
        await Swal.fire({
            title: 'Data Berhasil Diubah',
            text: 'Data Anda telah berhasil diperbarui.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });

        // Kirim data form menggunakan AJAX
        const formData = new FormData(document.querySelector('form'));
        fetch('profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Reload halaman setelah sukses
            setTimeout(() => {
                location.reload();
            }, 100);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

// Menambahkan event listener untuk konfirmasi ketika meninggalkan halaman
window.addEventListener('beforeunload', function (event) {
    if (formChanged) {
        // Munculkan pesan konfirmasi jika ada perubahan
        const message = "Anda telah mengubah data, tetapi belum menyimpan perubahan. Apakah Anda yakin ingin meninggalkan halaman?";
        event.returnValue = message; // Untuk sebagian besar browser
        return message; // Untuk beberapa browser lain yang lebih lama
    }
});

// Menambahkan konfirmasi logout
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
            window.location.href = 'logout.php'; 
        }
    });
}
</script>

</body>
</html>