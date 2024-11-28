<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin</title>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<style>
body {
    display: flex;
    min-height: 100vh;
    margin: 0;
    font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.sidebar {
    width: 250px;
    background-color: #343a40;
    color: white;
    height: 100vh;
    position: fixed;
    padding-top: 20px;
}
.sidebar a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    display: block;
}
.sidebar a:hover {
    background-color: #495057;
}
.content {
    margin-left: 250px;
    padding: 20px;
    flex: 1;
}
.card-container {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Jarak antar box */
    flex-wrap: wrap; /* Agar responsif */
}
h2 {
    padding: 10px;
    color: white;
    border-radius: 5px;
    text-align: center;
    margin: 0; /* Menghapus margin bawaan */
}
.card {
    flex: 1;
    max-width: 30%; /* Lebar maksimal untuk box */
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    padding: 15px;
}
.green-bg {
    background-color: green;
}
.red-bg {
    background-color: red;
}
.yellow-bg {
    background-color: yellow;
    color: black; /* Kontras untuk teks di latar belakang kuning */
}
.card a {
    text-decoration: none;
    color: inherit; /* Warna sesuai dengan latar belakang */
    font-size: 18px;
    font-weight: bold;
    display: block;
}
</style>
</head>
<body>
<div class="sidebar">
    <div class="user-panel text-center mb-4">
        <img src="../img/person.svg" alt="admin" width="20%">
        <p class="mt-2"><i class="fa fa-circle text-success"></i> logged in</p>
    </div>
    <ul class="list-unstyled">
        <li><a href="index.php"><i class="fa fa-home me-2"></i> Beranda</a></li>
        <li><a href="rooms.php"><i class="fa fa-lock me-2"></i> Cek Kamar</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>
<div class="content">
    <header>
        <h1 class="text-center mb-5">Admin Portal</h1>
    </header>

    <!-- Box Elements -->
    <div class="card-container">
        <div class="card green-bg">
            <a href="available_rooms.php">Total Kamar Tersedia: <?= $room; ?></a>
        </div>
        <div class="card red-bg">
            <a href="occupied_rooms.php">Total Kamar Terpakai: <?= $room; ?></a>
        </div>
        <div class="card yellow-bg">
            <a href="pending_rooms.php">Total Kamar Terpending: <?= $room; ?></a>
        </div>
    </div>
</div>

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
            window.location.href = '../logout.php'; // Ganti URL sesuai dengan rute logout Anda
        }
    });
}
</script>
</body>
</html>
