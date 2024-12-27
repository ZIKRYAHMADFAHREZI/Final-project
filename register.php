<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi</title>
<link rel="stylesheet" href="css/style.css">
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .show-password-icon {
        position: absolute;
        top: 50%;
        right: 30px; /* Pindahkan ikon lebih ke kiri */
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
        color: #888;
        user-select: none;
    }
    .input-group__input {
        font: inherit;
        color: #000;
        padding: 10px 0px 10px 0px; /* Ruang untuk ikon */
        width: 100%; /* Input disesuaikan dengan lebar container */
        border: 1px solid #ccc;
        padding-left: 10px;
        border-radius: 10px;
        outline: none;
        background-color: #f8f9fa;
        transition: border-color 500ms;
    }
    .input-group {
    position: relative;
    margin-bottom: 25px; /* Tambahkan ruang di bawah grup */
    }
    #username-error {
        position: absolute;
        top: 45px; /* Geser elemen ke atas */
        left: 0;
        font-size: 12px;
        color: red;
        display: none;
    }
</style>
</head>
<body>
    <div class="login-container">
        <h2 class="title">Halaman Register</h2>
        <form id="register-form" method="post">
        <div class="input-group">
            <input type="text" name="username" id="username" class="input-group__input" required>
            <label for="username" class="input-group__label">Username</label>
            <small id="username-error">Username sudah digunakan</small>
        </div>

            <div class="input-group">
                <input type="email" name="email" id="email" class="input-group__input" required>
                <label for="email" class="input-group__label">Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" class="input-group__input" minlength="8" required>
                <label for="password" class="input-group__label">Password</label>
                <span class="show-password-icon" id="toggle-password">&#x1F512;</span>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" class="input-group__input" minlength="8" required>
                <label for="confirm_password" class="input-group__label">Konfirmasi Password</label>
                <span class="show-password-icon" id="toggle-confirm-password">&#x1F512;</span>
            </div>
            <button type="submit" name="submit" id="register">Register</button>
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </form>
        <script>
            const inputPassword = document.getElementById("password");
            const inputConfirmPassword = document.getElementById("confirm_password");
            const togglePassword = document.getElementById("toggle-password");
            const toggleConfirmPassword = document.getElementById("toggle-confirm-password");
        
            togglePassword.addEventListener("click", () => {
                // Toggle password visibility for password input
                const passwordType = inputPassword.getAttribute("type") === "password" ? "text" : "password";
                inputPassword.setAttribute("type", passwordType);
        
                // Change icon between locked and unlocked
                const iconType = passwordType === "password" ? "\u{1F512}" : "\u{1F513}";
                togglePassword.textContent = iconType; // Set the icon based on the type
            });
        
            toggleConfirmPassword.addEventListener("click", () => {
                // Toggle password visibility for confirm password input
                const confirmPasswordType = inputConfirmPassword.getAttribute("type") === "password" ? "text" : "password";
                inputConfirmPassword.setAttribute("type", confirmPasswordType);
        
                // Change icon between locked and unlocked
                const iconType = confirmPasswordType === "password" ? "\u{1F512}" : "\u{1F513}";
                toggleConfirmPassword.textContent = iconType; // Set the icon based on the type
            });
        </script>
    </div>
<script src="js/regis.js"></script>
<script>
const inputs = document.querySelectorAll('.input-group__input');

inputs.forEach(input => {
// Tambahkan atau hapus kelas 'has-value' saat input memiliki nilai
input.addEventListener('input', () => {
    if (input.value.trim() !== '') {
    input.classList.add('has-value');
    } else {
    input.classList.remove('has-value');
    }
});

// Pastikan input yang sudah diisi sebelum halaman dimuat tetap memiliki gaya
if (input.value.trim() !== '') {
    input.classList.add('has-value');
}
  });

  document.getElementById("username").addEventListener("input", function () {
    const username = this.value.trim();
    const errorElement = document.getElementById("username-error");

    if (username) {
        fetch("check_username.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `username=${encodeURIComponent(username)}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "unavailable") {
                errorElement.style.display = "block";
            } else {
                errorElement.style.display = "none";
            }
        })
        .catch(err => console.error("Error:", err));
    } else {
        errorElement.style.display = "none";
    }
})
</script>

</body>
</html>