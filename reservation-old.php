<?php
session_start();
require "admin/includes/functions.php";
require "admin/includes/db.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['submit'])) {
		$guest = preg_replace("#[^0-9]#", "", $_POST['guest']);
		$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		$phone = preg_replace("#[^0-9]#", "", $_POST['phone']);
		$date_res = htmlentities($_POST['date_res'], ENT_QUOTES, 'UTF-8');
		$time = htmlentities($_POST['time'], ENT_QUOTES, 'UTF-8');
		$suggestions = htmlentities($_POST['suggestions'], ENT_QUOTES, 'UTF-8');

		if ($guest != "" && $email && $phone != "" && $date_res != "" && $time != "" && $suggestions != "") {
			$check = $db->query("SELECT * FROM reservation WHERE no_of_guest='" . $guest . "' AND email='" . $email . "' AND phone='" . $phone . "' AND date_res='" . $date_res . "' AND time='" . $time . "' LIMIT 1");

			if ($check->num_rows) {
				$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Anda sudah melakukan reservasi dengan informasi yang sama sebelumnya</p>";
			} else {
				$insert = $db->query("INSERT INTO reservation(no_of_guest, email, phone, date_res, time, suggestions) VALUES('" . $guest . "', '" . $email . "', '" . $phone . "', '" . $date_res . "', '" . $time . "', '" . $suggestions . "')");

				if ($insert) {
					$ins_id = $db->insert_id;
					$reserve_code = "UNIQUE_$ins_id" . substr($phone, 3, 8);
					$msg = "<p style='padding: 15px; color: green; background: #eeffee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Reservasi berhasil dilakukan. Kode reservasi Anda adalah $reserve_code. Harap dicatat bahwa reservasi akan kedaluwarsa setelah satu jam</p>";
				} else {
					$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Gagal melakukan reservasi. Silakan coba lagi</p>";
				}
			}
		} else {
			$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Data formulir tidak lengkap atau tipe data tidak valid</p>";
			print_r($_POST);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <title>MFORS</title>
  <link rel="stylesheet" href="css/main.css" />
  <script src="js/jquery.min.js"></script>
  <script src="js/myscript.js"></script>
</head>

<body>

  <?php require "includes/header.php"; ?>

  <div class="parallax" onclick="remove_class()">
    <div class="parallax_head">
      <h2>Reservasi</h2>
      <h3>Meja Makan</h3>
    </div>
  </div>

  <div class="content" onclick="remove_class()">
    <div class="inner_content">
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="hr_book_form">
        <h2 class="form_head"><span class="book_icon">PESAN MEJA</span></h2>
        <p class="form_slg">Kami menawarkan layanan reservasi terbaik untuk Anda</p>
        <?php echo "<br/>" . $msg; ?>
        <div class="left">
          <div class="form_group">
            <label>Jumlah Tamu</label>
            <input type="number" placeholder="Berapa tamu yang hadir" min="1" name="guest" id="guest" required>
          </div>
          <div class="form_group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan email Anda" required>
          </div>
          <div class="form_group">
            <label>Nomor Telepon</label>
            <input type="text" name="phone" placeholder="Masukkan nomor telepon" required>
          </div>
          <div class="form_group">
            <label>Tanggal</label>
            <input type="date" name="date_res" placeholder="Pilih tanggal reservasi" required>
          </div>
          <div class="form_group">
            <label>Waktu</label>
            <input type="time" name="time" placeholder="Pilih waktu reservasi" required>
          </div>
        </div>
        <div class="left">
          <div class="form_group">
            <label>Saran <small><b>(Contoh: Jumlah piring, pengaturan meja, dll)</b></small></label>
            <textarea name="suggestions" placeholder="Masukkan saran Anda" required></textarea>
          </div>
          <div class="form_group">
            <input type="submit" class="submit" name="submit" value="BUAT RESERVASI" />
          </div>
        </div>
        <p class="clear"></p>
      </form>
    </div>
  </div>

  <div class="content" onclick="remove_class()">
    <div class="inner_content">
      <div class="contact">
        <div class="left">
          <h3>LOKASI</h3>
          <p>Buk New Site, Kampus Baru</p>
          <p>Kota Kano</p>
        </div>
        <div class="left">
          <h3>KONTAK</h3>
          <p>08054645432, 07086898709</p>
          <p>Website@gmail.com</p>
        </div>
        <p class="left"></p>
        <div class="icon_holder">
          <a href="#"><img src="image/icons/Facebook.png" alt="Facebook" /></a>
          <a href="#"><img src="image/icons/Google+.png" alt="Google+" /></a>
          <a href="#"><img src="image/icons/Twitter.png" alt="Twitter" /></a>
        </div>
      </div>
    </div>
  </div>

  <div class="footer_parallax" onclick="remove_class()">
    <div class="on_footer_parallax">
      <p>&copy; <?php echo strftime("%Y", time()); ?> <span>RestoranSaya</span>. Semua Hak Dilindungi</p>
    </div>
  </div>

</body>

</html>