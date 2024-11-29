<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="icon website" type="png" href="img/logoo.png">
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light" style="background-color: #a1a0a5 !important; position: fixed; width: 100%; z-index: 1000;">
    <a class="navbar-brand" id="name" style="color: white; margin-left: 20px; cursor: pointer;">Grand Mutiara</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item active">
                <a class="nav-link" id="home" style="color: white; margin-left: 20px;">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="about" style="color: white; margin-left: 20px;">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="login" style="color: white; margin-left: 20px;">Log in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="regist" style="color: white; margin-left: 20px; margin-right:20px;">Sign in</a>
            </li>
        </ul>
    </div>
</nav>


<div id="loading" class="loading">
    <div class="spinner"></div>
    <h2 class="loading-text">GRAND MUTIARA</h2>
</div>

<div class="form-container">
    <h1>Form Profil Pengguna</h1>
    <form action="user.php" method="POST">
        <div class="form-group">
            <label for="first_name">Nama Depan:</label>
            <input type="text" id="first_name" name="first_name" value="{{ user.first_name }}" required>
        </div>
        <div class="form-group">
            <label for="last_name">Nama Belakang:</label>
            <input type="text" id="last_name" name="last_name" value="{{ user.last_name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ user.email }}" required>
        </div>
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1">+62</span>
            <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        <div class="form-group">
            <label for="date_of_birth">Tanggal Lahir:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ user.date_of_birth }}" required>
        </div>
        <div class="form-group">
            <button type="submit">Simpan</button>
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