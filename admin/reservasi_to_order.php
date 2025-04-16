<?php
require "includes/functions.php";
require "includes/db.php";

if (!isset($_GET['reservation_id']) || !is_numeric($_GET['reservation_id'])) {
  echo "ID reservasi tidak valid.";
  exit;
}

$reservation_id = (int)$_GET['reservation_id'];

// Cek apakah reservasi ada
$check = $db->query("SELECT * FROM reservation WHERE reserve_id = $reservation_id LIMIT 1");
if ($check->num_rows == 0) {
  echo "Reservasi tidak ditemukan.";
  exit;
}
