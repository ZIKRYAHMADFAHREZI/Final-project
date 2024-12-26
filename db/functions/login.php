<?php
session_start();
require '../connection.php';

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($login, $password, $remember = false) {
        $login = trim(htmlspecialchars($login));

        try {
            // Cek apakah login menggunakan email atau username
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :login OR username = :login");
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $this->startSession($user);

                if ($remember) {
                    $this->setRememberToken($user['id_user']);
                }

                $this->redirectByRole($user['role']);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function rememberMe() {
        if (isset($_COOKIE['remember_token'])) {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE remember_token IS NOT NULL");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $user) {
                    if (password_verify($_COOKIE['remember_token'], $user['remember_token'])) {
                        $this->startSession($user);
                        $this->redirectByRole($user['role']);
                        return true;
                    }
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }
        return false;
    }

    private function startSession($user) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
    }

    private function setRememberToken($id_user) {
        $token = bin2hex(random_bytes(32));
        $hashed_token = password_hash($token, PASSWORD_DEFAULT);
        $expiry = time() + (30 * 24 * 60 * 60); // 30 hari

        try {
            $stmt = $this->pdo->prepare("UPDATE users SET remember_token = :token WHERE id_user = :id_user");
            $stmt->execute(['token' => $hashed_token, 'id_user' => $id_user]);
            setcookie('remember_token', $token, $expiry, "/", "", false, true);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
        }
    }

    private function redirectByRole($role) {
        if ($role === 'admin') {
            header("Location: ../../dashboard/index.php");
        } elseif ($role === 'user') {
            header("Location: ../../index.php");
        }
        exit();
    }
}

$error = "";
$auth = new Auth($pdo);

// Login Proses
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($auth->login($login, $password, $remember)) {
        exit();
    } else {
      header('Location: ../../login.php?error=invalid_credentials');
      exit();
  }
}

// Remember Me Check
$auth->rememberMe();
?>