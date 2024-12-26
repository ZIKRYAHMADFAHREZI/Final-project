<?php
session_start();

class PaymentSystem {
    private $pdo;
    private $id_user;
    public $paymentDetails = [];
    public $total_amount = 0.0;
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->id_user = $_SESSION['id_user'] ?? null;

        if (!$this->id_user) {
            header('Location: login.php'); // Redirect jika user tidak login
            exit();
        }
    }

    public function uploadPaymentProof($file) {
        $fileTmp = $file['tmp_name'];
        $originalFileName = $file['name'];
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $this->allowedExtensions)) {
            return "Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.";
        }

        $newFileName = date('YmdHis') . '_' . uniqid() . '.' . $fileExtension;
        $uploadDir = '../../paynt/uploads/';
        $targetFile = $uploadDir . $newFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmp, $targetFile)) {
            return $newFileName;
        } else {
            return "Terjadi kesalahan saat mengunggah file.";
        }
    }

    public function getPrice($idRoom, $hour) {
        $stmt = $this->pdo->prepare("SELECT types.transit, types.12hour, types.24hour 
                                     FROM rooms 
                                     JOIN types ON rooms.id_type = types.id_type 
                                     WHERE rooms.id_room = :id_room");
        $stmt->bindParam(':id_room', $idRoom, PDO::PARAM_INT);
        $stmt->execute();

        $price = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$price) {
            throw new Exception("Data harga tidak ditemukan untuk kamar yang dipilih.");
        }

        switch ($hour) {
            case '3 jam':
                return $price['transit'];
            case '12 jam':
                return $price['12hour'];
            case '24 jam':
                return $price['24hour'];
            default:
                throw new Exception("Durasi tidak valid.");
        }
    }

    public function processReservation($data) {
        $startDate = $data['start_date'];
        $toDate = $data['to_date'] ?? null;
        $idPayMethod = $data['id_pay_method'];
        $totalAmount = (float)$data['total_amount'];
        $idRoom = $data['id_room'];
        $hour = $data['hour'];

        $idPayMethod = $data['id_pay_method'];
        $query = $this->pdo->prepare("SELECT * FROM pay_methods WHERE id_pay_method = :id_pay_method AND active = 1");
        $query->bindParam(':id_pay_method', $idPayMethod, PDO::PARAM_INT);
        $query->execute();

        $this->paymentDetails = $query->fetch(PDO::FETCH_ASSOC);
        $this->total_amount = (float)$data['total_amount'];

        $stmt = $toDate ? $this->pdo->prepare(
            "INSERT INTO reservations (id_user, id_pay_method, start_date, to_date, id_room, total_amount, hour) 
             VALUES (:id_user, :id_pay_method, :start_date, :to_date, :id_room, :total_amount, :hour)"
        ) : $this->pdo->prepare(
            "INSERT INTO reservations (id_user, id_pay_method, start_date, id_room, total_amount, hour) 
             VALUES (:id_user, :id_pay_method, :start_date, :id_room, :total_amount, :hour)"
        );

        if ($toDate) {
            $stmt->bindParam(':to_date', $toDate);
        }

        $stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
        $stmt->bindParam(':id_pay_method', $idPayMethod, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':id_room', $idRoom, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':hour', $hour);
        $stmt->execute();

        $_SESSION['id_reservation'] = $this->pdo->lastInsertId();
    }

    public function updatePaymentProof($idReservation, $idRoom, $paymentProof) {
        // Log parameter untuk debugging
        error_log("ID Reservation: " . $idReservation);
        error_log("ID Room: " . $idRoom);
        error_log("Payment Proof: " . $paymentProof);
    
        try {
            // Update payment_proof di tabel reservations
            $stmt = $this->pdo->prepare("UPDATE reservations SET payment_proof = :payment_proof WHERE id_reservation = :id_reservation");
            $stmt->bindParam(':payment_proof', $paymentProof);
            $stmt->bindParam(':id_reservation', $idReservation, PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                throw new Exception("Gagal memperbarui bukti pembayaran: " . implode(", ", $stmt->errorInfo()));
            }
    
            // Periksa keberadaan ID Room di tabel rooms
            $checkRoomStmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id_room = :id_room");
            $checkRoomStmt->bindParam(':id_room', $idRoom, PDO::PARAM_INT);
            $checkRoomStmt->execute();
    
            if ($checkRoomStmt->rowCount() === 0) {
                throw new Exception("ID Room tidak ditemukan di database.");
            }
    
            // Update status kamar menjadi 'pending'
            $stmt = $this->pdo->prepare("UPDATE rooms SET status = 'pending' WHERE id_room = :id_room");
            $stmt->bindParam(':id_room', $idRoom, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                error_log("Status kamar berhasil diperbarui.");
            } else {
                throw new Exception("Gagal memperbarui status kamar: " . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            error_log("Kesalahan: " . $e->getMessage());
            throw $e;
        }
    }    
    
}

require dirname(__DIR__, 2) . '/db/connection.php';
$paymentSystem = new PaymentSystem($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['submit-payment']) && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
            $uploadResult = $paymentSystem->uploadPaymentProof($_FILES['payment_proof']);
            if (strpos($uploadResult, '.') !== false) {
                $paymentSystem->updatePaymentProof($_SESSION['id_reservation'], $_POST['id_room'], $uploadResult);
                header('Location: ../../invoice.php?id_reservation=' . $_SESSION['id_reservation']);
                exit();
            } else {
                $errorMessage = $uploadResult;
            }
        } elseif (isset($_POST['start_date'], $_POST['id_pay_method'], $_POST['total_amount'], $_POST['id_room'], $_POST['hour'])) {
            $paymentSystem->processReservation($_POST);
            $successMessage = "Pemesanan berhasil. Silakan lanjutkan pembayaran.";
        } else {
            $errorMessage = "Data pemesanan tidak lengkap.";
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
// Panggil metode:
$paymentSystem->processReservation($_POST);

// Akses di luar metode:
$paymentDetails = $paymentSystem->paymentDetails;
$total_amount = $paymentSystem->total_amount;
?>