<?php
require '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_type = $_POST['tipe'];
    $number_room = $_POST['nomor'];

    if (!empty($id_type) && !empty($number_room) && is_numeric($number_room)) {
        try {
            // Periksa apakah nomor kamar sudah ada
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM rooms WHERE id_type = :id_type AND number_room = :number_room");
            $checkStmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            $checkStmt->bindParam(':number_room', $number_room, PDO::PARAM_INT);
            $checkStmt->execute();
            $roomExists = $checkStmt->fetchColumn();

            if ($roomExists > 0) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: 'Nomor kamar tersebut sudah ada dalam tipe yang dipilih.',
                            confirmButtonColor: '#007bff'
                        });
                    });
                </script>";
            } else {
                // Prepare the insert statement
                $stmt = $pdo->prepare("INSERT INTO rooms (id_type, number_room) VALUES (:id_type, :number_room)");
                $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
                $stmt->bindParam(':number_room', $number_room, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan!',
                                confirmButtonColor: '#007bff'
                            });
                        });
                    </script>";
                } else {
                    $errorInfo = $stmt->errorInfo();
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan!',
                                text: 'Gagal menyimpan data: " . addslashes($errorInfo[2]) . "',
                                confirmButtonColor: '#007bff'
                            });
                        });
                    </script>";
                }
            }
        } catch (PDOException $e) {
            echo "<script>alert('Kesalahan: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Harap isi semua bidang dengan benar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Kamar</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" 
    rel="stylesheet"
/>
<link rel="stylesheet" href="../css/admin.css">
<!-- Previous head content remains the same -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .toggle-btn {
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #343a40;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px;
        cursor: pointer;
        z-index: 1000;
        transition: left 0.3s ease-in-out;
    }
    .toggle-btn.closed {
        left: 15px;
    }
    section {
        margin: 20px 0;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    section select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    section label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #555;
    } 
    section {
        margin: 20px 0;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    section select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    section label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #555;
    }
    input.number_room {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    label.number {
        font-weight: bold;
        color: #555;
        margin-top: 15px;
        display: block;
    }
    #submit {
        display: inline-block;
        width: 100%;
        padding: 10px 15px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="user-panel text-center mb-4">
        <img src="../img/person.svg" alt="admin" width="20%">
        <p class="mt-2"><i class="fa fa-circle text-success"></i> logged in</p>
    </div>
    <ul class="list-unstyled">
        <li><a href="index.php"><i class="fa fa-home me-2"></i> Beranda</a></li>
        <li>
            <a href="#" data-bs-toggle="collapse" data-bs-target="#dropdownMenu" aria-expanded="false" aria-controls="dropdownMenu">
                <i class="fa fa-list me-2"></i> Kamar <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="collapse list-unstyled ms-3" id="dropdownMenu">
                <li><a href="delete_rooms.php" class="dropdown-item">Hapus kamar</a></li>
                <li><a href="rooms.php" class="dropdown-item">Status kamar</a></li>
                <li><a href="add_rooms.php" class="dropdown-item">Tambah Kamar</a></li>
                <li><a href="update_type.php" class="dropdown-item">Update Tipe</a></li>
            </ul>
        </li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>


<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content" id="content">
    <header>
        <h1 class="text-center mb-5">Tambah Kamar</h1>
    </header>
    <form action="" method="POST" class="p-3 border rounded">
    <section>
        <label for="tipe" class="form-label">Pilih Tipe</label>
        <select name="tipe" id="tipe" class="form-select" required>
            <option value="" disabled selected>Pilih Tipe</option>
            <?php
            $stmt = $pdo->query("SELECT id_type, name_type FROM types");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id_type']}'>{$row['name_type']}</option>";
            }
            ?>
        </select>

        <label for="nomor" class="number form-label">Masukkan Nomor Kamar</label>
        <input type="number" name="nomor" id="nomor" class="number_room form-control" required>
        <button type="submit" class="mt-3 btn btn-primary" id="submit">Simpan</button>
    </section>
    </form>
</div>

<script src="../js/admin.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// SweetAlert2 untuk tombol submit
document.getElementById('submitButton').addEventListener('click', function (e) {
    Swal.fire({
        title: 'Konfirmasi Ubah Data',
        text: "Apakah Anda yakin ingin mengubah email dan password?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('updateForm').submit(); // Submit form
        }
    });
});

// Sidebar Toggle
document.getElementById("toggle-btn").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    sidebar.classList.toggle("closed");
    content.classList.toggle("expanded");
});

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
            window.location.href = '../logout.php';
        }
    });
}
</script>
</body>
</html>