<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require 'db/connection.php';

// UserProfileManager class definition
class UserProfileManager {
    private $pdo;
    private $id_user;

    // Constructor to initialize PDO and user ID
    public function __construct($pdo, $id_user) {
        $this->pdo = $pdo;
        $this->id_user = $id_user;
    }

    // Get the user data from the 'users' table
    public function getUserData() {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $this->id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get the user profile data from the 'user_profile' table
    public function getUserProfile() {
        $stmt = $this->pdo->prepare("SELECT * FROM user_profile WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $this->id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update or insert the user profile data
    public function updateUserProfile($data) {
        try {
            // Check if the email is already taken by another user
            $stmt = $this->pdo->prepare("SELECT id_user FROM user_profile WHERE email = :email AND id_user != :id_user");
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':id_user', $this->id_user, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return "Kesalahan: Email sudah digunakan oleh pengguna lain.";
            }

            // If date_of_birth is empty, set it to NULL
            if (empty($data['date_of_birth'])) {
                $data['date_of_birth'] = NULL;
            }

            // Check if user profile exists
            $profile = $this->getUserProfile();
            if ($profile) {
                // Update user profile
                $stmt = $this->pdo->prepare("UPDATE user_profile SET
                    username = :username,
                    first_name = :first_name,
                    last_name = :last_name,
                    phone_number = :phone_number,
                    email = :email,
                    date_of_birth = :date_of_birth
                    WHERE id_user = :id_user");
            } else {
                // Insert new user profile
                $stmt = $this->pdo->prepare("INSERT INTO user_profile 
                    (id_profile, id_user, username, first_name, last_name, phone_number, email, date_of_birth) 
                    VALUES (NULL, :id_user, :username, :first_name, :last_name, :phone_number, :email, :date_of_birth)");
            }

            // Bind parameters and execute the profile update/insert query
            $stmt->bindParam(':id_user', $this->id_user, PDO::PARAM_INT);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':phone_number', $data['phone_number']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':date_of_birth', $data['date_of_birth'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Update the users table as well
                $stmt = $this->pdo->prepare("UPDATE users SET 
                    username = :username,
                    email = :email
                    WHERE id_user = :id_user");
                $stmt->bindParam(':id_user', $this->id_user, PDO::PARAM_INT);
                $stmt->bindParam(':username', $data['username']);
                $stmt->bindParam(':email', $data['email']);

                if ($stmt->execute()) {
                    return "Data berhasil disimpan!";
                } else {
                    return "Gagal memperbarui data di tabel users.";
                }
            } else {
                return "Gagal menyimpan data di tabel user_profile.";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "Kesalahan: Data yang dimasukkan sudah ada di sistem.";
            } else {
                return "Kesalahan: " . $e->getMessage();
            }
        }
    }
}

// Instantiate the UserProfileManager class
$id_user = $_SESSION['id_user'];
$manager = new UserProfileManager($pdo, $id_user);

// Retrieve user and user profile data
$user = $manager->getUserData();
$user_profile = $manager->getUserProfile();

// Handle the form submission to update user profile
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => $_POST['username'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'phone_number' => $_POST['phone_number'],
        'email' => $_POST['email'],
        'date_of_birth' => $_POST['date_of_birth']
    ];
    $message = $manager->updateUserProfile($data);
}

?>