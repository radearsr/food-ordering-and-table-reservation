<?php
session_start();
require "admin/includes/functions.php";
require "admin/includes/db.php";

$get_recent = $db->query("SELECT * FROM food LIMIT 9");

$result = "";

if ($get_recent->num_rows) {
	while ($row = $get_recent->fetch_assoc()) {
		$result .= "<div class='parallax_item'>
							<a href='detail.php?fid=" . $row['id'] . "'>
								<img src='image/FoodPics/" . $row['id'] . ".jpg' width='80px' height='80px' /> 
								<div class='detail'>
									<h4>" . $row['food_name'] . "</h4>
									<p class='desc'>" . substr($row['food_description'], 0, 33) . "...</p>
									<p class='price'>Rp " . $row['food_price'] . "</p>
								</div>
								<p class='clear'></p>
							</a>
						</div>";
	}
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Restoran kami menyajikan makanan segar dan layanan terbaik." />
	<meta name="keywords" content="restoran, makanan, reservasi, menu, MFORS" />

	<title>Majapahit Cafe</title>

	<link rel="stylesheet" href="css/main.css" />
	<script src="js/jquery.min.js"></script>
	<script src="js/myscript.js"></script>

	<style>
		img[src*="https://cloud.githubusercontent.com/assets/23024110/20663010/9968df22-b55e-11e6-941d-edbc894c2b78.png"] {
			display: none;
		}
	</style>
</head>

<body>

	<?php require "includes/header.php"; ?>

	<div class="parallax" onclick="remove_class()">
		<div class="parallax_head">
			<h2>Selamat Datang</h2>
			<h3>Kami Senang Memasak Untuk Anda</h3>
		</div>
	</div>

	<div class="content" onclick="remove_class()">
		<a href="reservation.php" class="submit">PESAN MEJA</a>
	</div>

	<div class="content remove_pad" onclick="remove_class()">
		<div class="inner_content on_parallax">
			<h2><span class="fresh">Temukan Menu Terbaru</span></h2>
			<div class="parallax_content">
				<?php echo $result; ?>
				<p class="clear"></p>
			</div>
		</div>
	</div>

	<div class="content" onclick="remove_class()">
		<div class="inner_content">
			<div class="contact">
				<div class="left">
					<h3>LOKASI</h3>
					<p>Jalan, Majapahit</p>
				</div>

				<div class="left">
					<h3>KONTAK</h3>
					<p>Sani'atul Khuluq, 20670056</p>
					<p>62 896-5578-3344</p>
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
			<p>&copy; <?php echo strftime("%Y", time()); ?> <span>Majapahit Cafe</span>. Seluruh Hak Cipta Dilindungi.</p>
		</div>
	</div>

</body>

</html>