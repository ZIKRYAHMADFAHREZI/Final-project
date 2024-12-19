<?php 
require '../db/connection.php';

// Handle form submission for adding payment methods
// Handle form submission for adding payment methods
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['method'])) {
    $method = $_POST['method'];
    $payment_number = $_POST['payment_number'];
    $account_name = $_POST['account_name'];

    // Debugging output (untuk cek data yang diterima)
    // echo "Method: $method, Number: $payment_number, Name: $account_name";

    $stmt = $pdo->prepare("INSERT INTO pay_methods (id_pay_method, method, payment_number, account_name, active) VALUES (null, :method, :payment_number, :account_name, 1)");
    $stmt->bindParam(':method', $method);
    $stmt->bindParam(':payment_number', $payment_number);
    $stmt->bindParam(':account_name', $account_name);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Metode pembayaran berhasil ditambahkan.',
                icon: 'success'
            }).then(() => {
                window.location = 'payments.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menambahkan metode pembayaran.',
                icon: 'error'
            });
        </script>";
    }
}
// Toggle activation status
if (isset($_GET['toggle_id'])) {
    $id = $_GET['toggle_id'];
    $current_status = (int)$_GET['active'];
    $new_status = $current_status === 1 ? 0 : 1;

    $stmt = $pdo->prepare("UPDATE pay_methods SET active = :status WHERE id_pay_method = :id");
    $stmt->bindParam(':status', $new_status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("Location: payments.php");
    exit;
}

// Retrieve all payment methods
$stmt = $pdo->query("SELECT * FROM pay_methods");
$methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Pembayaran</title>
<link rel="icon" type="image/x-icon" href="../img/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/admin.css">
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
                <li><a href="rooms.php" class="dropdown-item">Status kamar</a></li>
                <li><a href="add_rooms.php" class="dropdown-item">Tambah Kamar</a></li>
                <li><a href="delete_rooms.php" class="dropdown-item">Hapus kamar</a></li>
            </ul>
        </li>
        <li><a href="payments.php"><i class="fa fa-credit-card me-2"></i> Pembayaran</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<div class="content" id="content">
    <header>
        <h1 class="text-center mb-5">Metode Pembayaran</h1>
    </header>

    <div class="container">
        <h2>Tambah Metode Pembayaran</h2>
        <form method="post" id="add-method-form">
            <div class="mb-3">
                <label for="method" class="form-label">Metode</label>
                <input type="text" name="method" id="method" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="payment_number" class="form-label">Nomor Rekening</label>
                <input type="number" name="payment_number" id="payment_number" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="account_name" class="form-label">Nama Akun</label>
                <input type="text" name="account_name" id="account_name" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>


        <h2 class="mt-5">Metode Pembayaran yang Tersedia</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Metode</th>
                    <th>Nama Akun</th>
                    <th>Nomor Rekening</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($methods as $method) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($method['method']) ?></td>
                    <td><?= htmlspecialchars($method['account_name']) ?></td>
                    <td><?= htmlspecialchars($method['payment_number']) ?></td>
                    <td>
                    <a href="?toggle_id=<?= $method['id_pay_method'] ?>&active=<?= $method['active'] ?>" class="btn btn-warning">
                        <?= $method['active'] == 1 ? 'Nonaktifkan' : 'Aktifkan'; ?>
                    </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
document.getElementById('add-method-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Mencegah pengiriman langsung

    Swal.fire({
        title: 'Konfirmasi Tambah',
        text: 'Apakah Anda yakin ingin menambahkan metode pembayaran ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Tambahkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form
            this.submit();
        }
    });
});

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/admin.js"></script>
</body>
</html>
