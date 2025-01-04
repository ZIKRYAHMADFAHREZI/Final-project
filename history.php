<?php 
require 'db/functions/history.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta
name="viewport"
content="width=device-width, initial-scale=1, shrink-to-fit=no"
/>
<title>History Pemesanan</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- Bootstrap CSS v5.2.1 -->
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
rel="stylesheet"
integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
crossorigin="anonymous"
/>

<link rel="stylesheet" href="css/history.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="tainer">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center">History Pemesanan</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-custom"><?= htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <?php if (count($reservations) > 0): ?>
                                    <?php foreach ($reservations as $index => $reservation): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars(date('d F Y', strtotime($reservation['reservation_date']))); ?></td>
                                            <td><?= htmlspecialchars($reservation['name_type']); ?></td>
                                            <td><?= htmlspecialchars($reservation['number_room']); ?></td>
                                            <td>Rp<?= number_format($reservation['total_amount'], 0, ',', '.'); ?></td>
                                            <td>
                                            <a href="#" onclick="showInvoiceFromFile('invoice.php?id_reservation=<?= urlencode($reservation['id_reservation']); ?>')">
                                                Lihat Invoice
                                            </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada data pemesanan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="invoiceModal" style="display:none;">
    <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
        <div id="modalContent" style="background-color: white; padding: 20px; border-radius: 10px; max-width: 90%; width: 1000px; max-height: 90vh; overflow-y: auto;">
            <div id="invoiceContent"></div> <!-- Konten invoice akan dimasukkan ke sini -->
            <button onclick="closeModal()" class="btn btn-secondary mt-3">Tutup</button>
        </div>
    </div>
</div>

<script>
function showInvoiceFromFile(fileUrl) {
    if (fileUrl) {
        var modal = document.getElementById('invoiceModal');
        var content = document.getElementById('invoiceContent');

        // Fetch konten dari file invoice
        fetch(fileUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal memuat invoice.');
                }
                return response.text();
            })
            .then(html => {
                content.innerHTML = html; // Masukkan konten file ke dalam modal
                modal.style.display = 'flex'; // Tampilkan modal
            })
            .catch(error => {
                content.innerHTML = `<p class="text-danger">Error: ${error.message}</p>`;
                modal.style.display = 'flex';
            });
    } else {
        alert('File invoice tidak ditemukan.');
    }
}

function closeModal() {
    var modal = document.getElementById('invoiceModal');
    modal.style.display = 'none'; // Sembunyikan modal
    document.getElementById('invoiceContent').innerHTML = ''; // Bersihkan konten modal
}

</script>
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
></script>
</body>
</html>
