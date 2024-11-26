<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganti Email & Password</title>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link rel="stylesheet" href="../css/admin.css">
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
        <h1>Ganti Email & Password</h1>
    </header>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input 
                    type="email" 
                    class="form-control" 
                    id="email_baru" 
                    name="email_baru"
                    placeholder="Masukkan Email Baru"
                >
            </div>
            <div class="mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_lama" 
                    name="password_lama"
                    placeholder="Password Lama" 
                    required
                >
            </div>
            <div class="mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_baru" 
                    name="password_baru" 
                    placeholder="Password Baru"
                    required
                >
            </div>
            <div class="mb-3">
                <input 
                    type="password" 
                    class="form-control" 
                    id="konfirmasi_password" 
                    name="konfirmasi_password"
                    placeholder="Konfirmasi Password Baru"
                    required
                >
            </div>
            <div class="d-flex justify-content-between">
                <button type="reset" class="btn btn-secondary">Cancel</button>
                <button type="submit" name="submit" class="btn btn-primary">Ubah Data!</button>
            </div>
        </form>
    </div>
</div>
<!-- sweet alert2 -->
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
