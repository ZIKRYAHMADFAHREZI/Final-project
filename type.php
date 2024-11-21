<?php
require 'db/connection.php';
$id = $_GET['id'];
$today = new DateTime();
$formattedDate = $today->format('Y-m-d'); // Format tanggal menjadi YYYY-MM-DD

$sql = "SELECT id_room FROM rooms";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tipe</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="icon" type="png" href="img/logoo.png">
<link rel="stylesheet" href="css/trans.css">
<style>
    body {
        background-color: #DCDCDC;
    }
    .container {
        padding-top: 70px;
    }
</style>
</head>
<body>
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

<form action="paynt/payment.php" method="post">
<div class="container">
        <h2 class="text-center">Pilih Tanggal dan Kamar</h2>
        <form action="paynt/payment.php" method="post">
            <div class="form-group">
                <label for="datePicker">Tanggal:</label>
                <input type="date" class="form-control" id="datePicker" min="<?php echo $formattedDate; ?>" required>
            </div>

            <div class="form-group">
            <label for="id_room">No Kamar:</label>
            <select class="form-control" id="id_room" name="id_room" required>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['id_room']; ?>">
                            <?php echo $row['id_room']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php else : ?>
                    <option value="">Tidak ada data</option>
                <?php endif; ?>
            </select>
        </div>

            <button type="submit" class="btn btn-primary btn-block mt-5" name="submit" id="submit">Pesan Sekarang</button>
        </form>
    </div>

    <script src="js/trans.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>