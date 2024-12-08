<?php 
header('Content-Type: application/json');

try {
  require '../db/connection.php';

  // ... existing code for processing price updates ...

  echo json_encode(['success' => true]);

} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}

try {
    require '../db/connection.php'; // Your database connection file

    $typeId = $_GET['id_type'] ?? null;
    
    if (!$typeId) {
        throw new Exception('Type ID is required');
    }

    // Get room rates
    $roomRatesStmt = $pdo->prepare("
        SELECT id_room_rate, `12hour`, `24hour`
        FROM room_rates
        WHERE id_type = ?
        GROUP BY `12hour`, `24hour`
        LIMIT 1
    ");
    $roomRatesStmt->execute([$typeId]);
    $roomRates = $roomRatesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get transit rates
    $transitStmt = $pdo->prepare("
        SELECT id_transit, price
        FROM transits
        WHERE id_type = ?
        GROUP BY price
        LIMIT 1
    ");
    $transitStmt->execute([$typeId]);
    $transits = $transitStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'room_rates' => $roomRates,
        'transits' => $transits
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// update_price.php
header('Content-Type: application/json');

try {
    require '../db/connection.php';

    $priceType = $_POST['priceType'] ?? null;
    $newPrice = $_POST['newPrice'] ?? null;

    if (!$priceType || !$newPrice) {
        throw new Exception('Price type and new price are required');
    }

    // Parse the price type value
    $parts = explode('_', $priceType);
    
    if ($parts[0] == 'room' && $parts[1] == 'rate') {
        // Update room rate
        $column = $parts[2] == '12' ? '12hour' : '24hour';
        $id = $parts[3];
        
        $stmt = $pdo->prepare("
            UPDATE room_rates 
            SET `$column` = ? 
            WHERE id_room_rate = ?
        ");
        $stmt->execute([$newPrice, $id]);
    } else if ($parts[0] == 'transit') {
        // Update transit rate
        $id = $parts[1];
        
        $stmt = $pdo->prepare("
            UPDATE transits 
            SET price = ? 
            WHERE id_transit = ?
        ");
        $stmt->execute([$newPrice, $id]);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganti Email & Password</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" 
    rel="stylesheet"
/>
<link rel="stylesheet" href="../css/admin.css">
<!-- Previous head content remains the same -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .toggle-btn {
        position: fixed;
        top: 15px;
        left: 15px;
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
        <li>
            <a href="#" 
            class="dropdown-toggle" 
            data-bs-toggle="collapse" 
            data-bs-target="#dropdownMenu" 
            aria-expanded="false" 
            aria-controls="dropdownMenu">
                <i class="fa fa-list me-2"></i> Kamar
            </a>
            <ul class="collapse list-unstyled ms-3" id="dropdownMenu">
<<<<<<< HEAD
<<<<<<< HEAD
                <li><a href="delete_rooms.php" class="dropdown-item">Hapus kamar</a></li>
                <li><a href="rooms.php" class="dropdown-item">Status kamar</a></li>
                <li><a href="add_rooms.php" class="dropdown-item">Tambah Kamar</a></li>
=======
                <li><a href="rooms.php" class="dropdown-item">Status Kamar</a></li>
                <li><a href="add_update.php" class="dropdown-item">Tambah Kamar</a></li>
>>>>>>> 43c5f023c4ffb67a08ba1f1b1d4495cd758f6d06
=======
                <li><a href="rooms.php" class="dropdown-item">Status Kamar</a></li>
                <li><a href="add_update.php" class="dropdown-item">Tambah Kamar</a></li>
>>>>>>> 43c5f023c4ffb67a08ba1f1b1d4495cd758f6d06
                <li><a href="update_type.php" class="dropdown-item">Update Tipe</a></li>
            </ul>
        </li>

        <li><a href="updatePw.php"><i class="fa fa-lock me-2"></i> Ganti Email & Password</a></li>
        <li><a href="#" onclick="confirmLogout();"><i class="fa fa-lock me-2"></i> Logout</a></li>
    </ul>
</div>


<!-- Toggle Button -->
<button class="toggle-btn" id="toggle-btn">☰</button>

<!-- Main Content -->
<div class="content text-center" id="content">
    <h1>Update Tipe</h1>
    <form action="" method="post" id="updateForm">         
        <section>         
            <label for="tipe" class="form-label">Pilih Tipe</label>         
            <select name="tipe" id="tipe" class="form-select" required>             
                <option value="" disabled selected>Pilih Tipe</option>             
                <?php             
                $stmt = $pdo->query("SELECT id_type, type FROM types");             
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {                 
                    echo "<option value='{$row['id_type']}'>{$row['type']}</option>";             
                }             
                ?>         
            </select>     

            <div id="hargaContainer" style="margin-top: 20px;">
                <label for="harga" class="form-label">Pilih Harga</label>         
                <select name="harga" id="harga" class="form-select" required>             
                    <option value="" disabled selected>Pilih Harga</option>             
                </select>
            </div>
        </section>

        <div class="mt-3">
            <label for="Type">Type:</label>
            <textarea id="Type" name="Type" rows="4" cols="50" class="form-control" placeholder="Tulis Type Anda di sini"></textarea>
        </div>

        <div class="mt-3">
            <label for="Description">Description:</label>
            <textarea id="Description" name="Description" rows="4" cols="50" class="form-control" placeholder="Tulis Description Anda di sini"></textarea>
        </div>

        <div class="mt-3">
            <label for="Long_description">Long Description:</label>
            <textarea id="Long_description" name="Long_description" rows="6" cols="50" class="form-control" placeholder="Tulis Long Description Anda di sini"></textarea>
        </div>
                
        <div class="mt-3">
            <button type="reset" class="btn btn-secondary">Cancel</button>
            <button type="button" id="submitButton" class="btn btn-primary">Ubah Data!</button>
        </div>
    </form>
</div>


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        document.getElementById('updateForm').addEventListener('submit', function(e) {
            e.preventDefault();});
document.getElementById('tipe').addEventListener('change', function() {
    var tipeId = this.value;
    var hargaSelect = document.getElementById('harga');
    
    // Show loading state
    hargaSelect.disabled = true;
    hargaSelect.innerHTML = '<option>Loading...</option>';
    
    // AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_harga.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status == 200) {
            hargaSelect.innerHTML = this.responseText;
            hargaSelect.disabled = false;
        } else {
            hargaSelect.innerHTML = '<option>Error loading prices</option>';
            console.error('Failed to load prices');
        }
    }
    
    xhr.onerror = function() {
        hargaSelect.innerHTML = '<option>Error loading prices</option>';
        console.error('Failed to load prices');
    }
    
    xhr.send('tipe_id=' + tipeId);
});

// Update your submit button handler
document.getElementById('submitButton').addEventListener('click', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Konfirmasi Ubah Data',
        text: "Apakah Anda yakin ingin mengubah data?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('updateForm').submit();
        }
    });
});
</script>
<script src="../js/admin.js"></script>
</body>
</html>