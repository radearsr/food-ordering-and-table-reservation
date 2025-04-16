<?php
require "admin/includes/db.php";

$reservation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($reservation_id > 0) {
  // Ambil data reservasi
  $res_query = $db->query("SELECT * FROM reservation WHERE reserve_id = '$reservation_id' LIMIT 1");
  if ($res_query->num_rows == 0) {
    die("Reservasi tidak ditemukan.");
  }
  $reservation = $res_query->fetch_assoc();
  // Ambil makanan yang dipesan
  $foods_query = $db->query("
        SELECT rf.quantity, f.food_name, f.food_price 
        FROM reservation_foods rf 
        JOIN food f ON rf.food_id = f.id 
        WHERE rf.reservation_id = '$reservation_id'
    ");
  $foods = [];
  $total = 0;
  while ($row = $foods_query->fetch_assoc()) {
    $foods[] = $row;
    $total += $row['quantity'] * $row['food_price'];
  }
} else {
  die("ID reservasi tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Pesanan</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #fff;
  }

  .header {
    background-color: #4caf50;
    color: white;
    padding: 15px 20px;
    font-weight: bold;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  th,
  td {
    padding: 10px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
  }

  th {
    color: #555;
    font-size: 14px;
  }

  .section {
    margin: 20px;
  }

  .item-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
  }

  .item-row strong {
    font-weight: bold;
  }

  .status {
    margin-top: 30px;
    background-color: #f8f8f8;
    padding: 15px;
    border-radius: 5px;
  }

  .status h3 {
    margin: 0 0 10px;
    font-size: 26px;
    color: #333;
  }

  .status p,
  .status ul {
    font-size: 22px;
    margin-top: 10px;
  }

  .status ul {
    list-style-type: disc;
    padding-left: 20px;
  }

  .detail-price {
    margin-top: 20px;
    background-color: #f8f8f8;
    padding: 15px;
    border-radius: 5px;
  }

  .detail-price h3 {
    font-size: 22px;
    margin-top: 10px;
  }

  .detail-price p {
    font-size: 22px;
    margin-top: 10px;
  }

  .btn-back {
    display: block;
    padding: 20px 20px;
    border: none;
    background-color: #4caf50;
    color: white;
    text-decoration: none;
    /* margin-top: 20px; */
    font-family: 28px;
    margin: 0 auto;
    outline: none;
    border-radius: 10px;
    width: 200px;
    margin-top: 20px;
    text-align: center;
  }
  </style>
</head>

<body>
  <div class="header">ORDER DETAILS</div>
  <div class="section">
    <table>
      <thead>
        <tr>
          <th>ORDER CODE</th>
          <th>GUEST</th>
          <th>SUGGESTION</th>
          <th>EMAIL</th>
          <th>PHONE</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($reservation['reservation_code']) ?></td>
          <td><?= htmlspecialchars($reservation['no_of_guest']) ?> Orang</td>
          <td><?= htmlspecialchars($reservation['suggestions']) ?: '-' ?></td>
          <td><?= htmlspecialchars($reservation['email']) ?></td>
          <td><?= htmlspecialchars($reservation['phone']) ?></td>
        </tr>
      </tbody>
    </table>

    <div class="item-row" style="margin-top: 30px;">
      <strong>Menu</strong>
      <strong>Qty</strong>
    </div>

    <?php if (count($foods) > 0): ?>
    <?php foreach ($foods as $food): ?>
    <div class="item-row">
      <span><?= htmlspecialchars($food['food_name']) ?></span>
      <span><?= $food['quantity'] ?></span>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="item-row">
      <span>Tidak ada makanan yang dipesan</span>
      <span>–</span>
    </div>
    <?php endif; ?>

    <div class="item-row" style="margin-top: 15px;">
      <strong>Total Price</strong>
      <strong>Rp <?= number_format($total, 0, ',', '.') ?></strong>
    </div>

    <!-- Instruksi Pembayaran -->
    <div class="status">
      <h3>Instruksi Pembayaran</h3>
      <?php if ($reservation['payment_method'] === 'kasir'): ?>
      <p>Silakan lakukan pembayaran langsung ke kasir</p>
      <?php elseif ($reservation['payment_method'] === 'transfer'): ?>
      <p>Silakan transfer ke rekening berikut:</p>
      <ul>
        <li><strong>Bank:</strong> BCA</li>
        <li><strong>Nomor Rekening:</strong> 1234567890</li>
        <li><strong>Atas Nama:</strong> PT Majapahit Cafe</li>
      </ul>
      <p>Setelah transfer, harap tunjukkan bukti pembayaran kepada kasir</p>
      <?php else: ?>
      <p>Metode pembayaran tidak diketahui.</p>
      <?php endif; ?>
    </div>

    <!-- Detail Harga -->
    <div class="detail-price" style="margin-top: 20px;">
      <h3>Detail Harga</h3>
      <p>Total harga yang harus dibayar adalah Rp <?= number_format($total, 0, ',', '.') ?></p>
    </div>
    <a href="/" class="btn-back">Kembali</a>
</body>

</html>