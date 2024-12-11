<?php
if (isset($_POST['submit'])) {
    // Mengambil data dari form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Password dan konfirmasi password tidak cocok!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else {
        try {
            // Periksa apakah username sudah ada
            $checkUser = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($checkUser);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Username sudah digunakan. Silakan pilih username lain.',
                        confirmButtonText: 'OK'
                    });
                </script>";
            } else {
                // Hash password sebelum menyimpan ke database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Query untuk memasukkan data ke dalam tabel dengan prepared statement
                $sql = "INSERT INTO users (username, email, password) 
                        VALUES (:username, :email, :password)";
                $stmt = $pdo->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);

                // Eksekusi query
                if ($stmt->execute()) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Registrasi Berhasil',
                            text: 'Silakan login.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../login.php';
                            }
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Registrasi Gagal',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            confirmButtonText: 'OK'
                        });
                    </script>";
                }
            }
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Server',
                    text: '" . $e->getMessage() . "',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    }
}
?>