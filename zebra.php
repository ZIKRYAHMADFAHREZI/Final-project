<div class="card" style="width: 18rem;">
    <img src="..." class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">Card title</h5>
      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      
      <!-- Menambahkan harga di sini -->
      <p class="card-price" style="font-weight: bold; color: green; margin-bottom: 20px;">Harga mulai dari Rp. 20.000</p>
      
      <a href="#" class="btn btn-primary">Go somewhere</a>
    </div>
  </div>


<!-- Cari  -->
  <div class="form-container">
    <form action="" method="post">
        <input 
            type="text" 
            name="keyword" 
            size="30" 
            autofocus 
            placeholder="Masukkan keyword pencarian" 
            autocomplete="off"
            class="form-control d-inline-block w-50 mb-2"
        >
        <button type="submit" name="cari" class="btn btn-primary">Cari!</button>
    </form>
</div>


<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radio Button Kotak</title>
    <style>
        .radio-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 kolom */
            gap: 10px; /* Jarak antar radio button */
            width: 200px; /* Lebar kotak */
            margin: 20px auto; /* Pusatkan kotak */
        }
        .radio-container label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .radio-container input[type="radio"] {
            display: none; /* Sembunyikan radio button asli */
        }
        .radio-container input[type="radio"]:checked + label {
            background-color: #007bff; /* Warna latar saat dipilih */
            color: white; /* Warna teks saat dipilih */
        }
    </style>
</head>
<body>

<div class="radio-container">
    <input type="radio" id="option1" name="numbers" value="1">
    <label for="option1">1</label>

    <input type="radio" id="option2" name="numbers" value="2">
    <label for="option2">2</label>

    <input type="radio" id="option3" name="numbers" value="3">
    <label for="option3">3</label>

    <input type="radio" id="option4" name="numbers" value="4">
    <label for="option4">4</label>

    <input type="radio" id="option5" name="numbers" value="5">
    <label for="option5">5</label>

    <input type="radio" id="option6" name="numbers" value="6">
    <label for="option6">6</label>

    <input type="radio" id="option7" name="numbers" value="7">
    <label for="option7">7</label>

    <input type="radio" id="option8" name="numbers" value="8">
    <label for="option8">8</label>

    <input type="radio" id="option9" name="numbers" value="9">
    <label for="option9">9</label>
</div>

</body>
</html> -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radio Button Kotak Kustom</title>
    <style>
        .number_room {
            display: flex; /* Menggunakan flexbox untuk menyusun radio button */
            gap: 10px; /* Jarak antar radio button */
            margin: 20px auto; /* Pusatkan kotak */
            flex-wrap: wrap; /* Membungkus ke baris berikutnya jika diperlukan */
            justify-content: center;
        }
        .number_room label {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer; /* Menunjukkan pointer saat hover */
        }
        .number_room input[type="radio"] {
            display: none; /* Sembunyikan radio button asli */
        }
        .custom-radio {
            width: 50px; /* Ukuran kotak */
            height: 50px; /* Ukuran kotak */
            appearance: none; /* Menghilangkan gaya default */
            background-color: green; /* Warna default kotak */
            border: 2px solid #000; /* Border kotak */
            border-radius: 3px; /* Sudut kotak */  
            cursor: pointer; /* Menunjukkan pointer saat hover */
            display: flex; /* Menggunakan flex untuk menempatkan angka */
            align-items: center; /* Pusatkan secara vertikal */
            justify-content: center; /* Pusatkan secara horizontal */
            color: white; /* Warna teks angka */
            font-size: 20px; /* Ukuran font angka */
        }
        .number_room input[type="radio"]:checked + label .custom-radio {
            background-color: yellow; /* Warna saat dicentang */
        }
    </style>
</head>
<body>

  <div class="form-group mt-2">
    <label for="number_room">No Kamar:</label>
    <div class="number_room" required>
        <?php if (!empty($number_room)): ?>
            <?php foreach ($number_room as $number): ?>
                <div class="form-check-inline">
                    <input type="radio" 
                           id="number<?= htmlspecialchars($number['number_room']) ?>" 
                           name="number_room" 
                           value="<?= htmlspecialchars($number['number_room']) ?>" 
                           class="custom-radio">
                    <label for="number<?= htmlspecialchars($number['number_room']) ?>"
                           class="form-check-label">
                        <?= htmlspecialchars($number['number_room']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">Tidak ada kamar</div>
        <?php endif; ?>
    </div>
    <div id="priceDisplay" class="mt-3 h4"></div>
</div>

</body>
</html>
<label for="phone">Enter your phone number:</label>
<input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}">


<!-- Gambar Auto Slide -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/<?= $type['img'] ?>_2.jpg" class="d-block w-100" alt="img" style="aspect-ratio: 19 / 8;">
        </div>
        <div class="carousel-item">
            <img src="img/<?= $type['img'] ?>_3.jpg" class="d-block w-100" alt="img" style="aspect-ratio: 19 / 8;">
        </div>
        <div class="carousel-item">
            <img src="img/<?= $type['img'] ?>_4.jpg" class="d-block w-100" alt="img" style="aspect-ratio: 19 / 8;">
        </div>
    </div>

    <!-- Tambahkan kontrol navigasi opsional -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>