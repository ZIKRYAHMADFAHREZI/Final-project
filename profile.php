<?php 
require 'db/functions/user_profile.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link rel="stylesheet" href="css/user_profile.css">
<!-- bootsrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<?php include 'navbar.php';?>
<div class="form-container">
    <h1>Profil Pengguna</h1>
    <div class="form-group">
    <form action="db/functions/user_profile.php" method="POST">
        <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" maxlength="254" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="first_name">Nama Depan:</label>
            <input type="text" id="first_name" name="first_name" maxlength="254" value="<?= htmlspecialchars($user_profile['first_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Nama Belakang:</label>
            <input type="text" id="last_name" name="last_name" maxlength="254" value="<?= htmlspecialchars($user_profile['last_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="phone_number">Nomor Telepon:</label>
            <input type="tel" id="phone_number" name="phone_number" maxlength="15" value="<?= htmlspecialchars($user_profile['phone_number'] ?? ''); ?>">
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
<script src="js/user_profile.js"></script>
<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- sweet alert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>