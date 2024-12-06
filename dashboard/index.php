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
<link rel="icon" type="png" href="../img/icon.png">
<style>
body {
    display: flex;
    min-height: 100vh;
    margin: 0;
    font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
    overflow-x: hidden;
}
.sidebar {
    width: 250px;
    background-color: #343a40;
    color: white;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transform: translateX(0);
    transition: transform 0.3s ease-in-out;
    z-index: 999;
    padding-top: 20px;
}
.sidebar.closed {
    transform: translateX(-100%);
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
    transition: margin-left 0.3s ease-in-out;
}
.content.expanded {
    margin-left: 0;
}
.toggle-btn {
    position: fixed;
    top: 15px; /* Memperbaiki posisi */
    left: 15px; /* Dekatkan ke sidebar */
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
.card-container {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Jarak antar box */
    flex-wrap: wrap; /* Agar responsif */
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
.card.green-bg {
    background-color: green;
    color: white;
}
.card.red-bg {
    background-color: red;
    color: white;
}
.card.yellow-bg {
    background-color: yellow;
    color: black; /* Kontras untuk teks */
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
        <li><a href="rooms.php"><i class="fa fa-lock me-2"></i> Cek Kamar</a></li>
        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content" id="content">
    <header>
        <h1 class="text-center mb-5">Admin Portal</h1>
    </header>

    <!-- Box Elements -->
    <div class="card-container mb-4">
        <div class="card green-bg">
            <p>Total Kamar Tersedia: <?= $room; ?></p>
        </div>
        <div class="card red-bg">
            <p>Total Kamar Terpakai: <?= $room; ?></p>
        </div>
        <div class="card yellow-bg">
            <p>Total Kamar Terpending: <?= $room; ?></p>
        </div>
    </div>

    <!-- Cari -->
    <div class="form-container text-center mt-5">
        <form action="" method="post" class="d-inline-block">
            <input 
                type="text" 
                name="keyword" 
                size="150%" 
                autofocus 
                placeholder="Masukkan keyword pencarian" 
                autocomplete="off"
                class="form-control d-inline-block w-50 mb-2"
            >
            <button type="submit" name="cari" class="btn btn-primary">Cari!</button>
        </form>
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

// Sidebar Toggle
document.getElementById("toggle-btn").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    sidebar.classList.toggle("closed");
    content.classList.toggle("expanded");
});
</script>
</body>
</html>
