<?php

session_start();
require "admin/includes/functions.php";
require "admin/includes/db.php";

$msg = "";

// Fetch available tables from database
$tables_query = $db->query("SELECT * FROM tables WHERE status = 'available'");
$tables = [];
while ($table = $tables_query->fetch_assoc()) {
	$tables[] = $table;
}

// Fetch food items from database
$food_query = $db->query("SELECT * FROM food");
$food_items = [];
$food_categories = [];

while ($item = $food_query->fetch_assoc()) {
	$food_items[] = $item;

	// Create a list of unique categories
	if (!in_array($item['food_category'], $food_categories)) {
		$food_categories[] = $item['food_category'];
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['submit'])) {

		$guest = preg_replace("#[^0-9]#", "", $_POST['guest']);
		$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		$phone = preg_replace("#[^0-9]#", "", $_POST['phone']);
		$date_res = htmlentities($_POST['date_res'], ENT_QUOTES, 'UTF-8');
		$time = htmlentities($_POST['time'], ENT_QUOTES, 'UTF-8');
		$suggestions = htmlentities($_POST['suggestions'], ENT_QUOTES, 'UTF-8');
		$table_id = intval($_POST['table_id']);
		$payment_method = htmlentities($_POST['payment_method'], ENT_QUOTES, 'UTF-8');

		// Process food menu selections
		$food_ids = isset($_POST['food_items']) ? $_POST['food_items'] : [];
		$food_quantities = isset($_POST['food_quantities']) ? $_POST['food_quantities'] : [];

		if ($guest != "" && $email && $phone != "" && $date_res != "" && $time != "" && $table_id > 0) {

			$check = $db->query("SELECT * FROM reservation WHERE table_id='" . $table_id . "' AND date_res='" . $date_res . "' AND time='" . $time . "' LIMIT 1");

			if ($check->num_rows) {

				$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Meja yang dipilih sudah direservasi untuk waktu tersebut. Silakan pilih meja atau waktu lain.</p>";
			} else {

				// Begin transaction
				$db->begin_transaction();

				try {
					// Insert reservation
					$insert = $db->query("INSERT INTO reservation(no_of_guest, email, phone, date_res, time, suggestions, table_id, payment_method) 
												VALUES('" . $guest . "', '" . $email . "', '" . $phone . "', '" . $date_res . "', '" . $time . "', '" . $suggestions . "', '" . $table_id . "', '" . $payment_method . "')");

					if ($insert) {
						$reservation_id = $db->insert_id;
						$reserve_code = "UNIQUE_$reservation_id" . substr($phone, 3, 8);

						// Update reservation code
						$db->query("UPDATE reservation SET reservation_code='" . $reserve_code . "' WHERE id='" . $reservation_id . "'");

						// Insert food selections
						if (count($food_ids) > 0) {
							foreach ($food_ids as $index => $food_id) {
								$food_id = intval($food_id);
								$quantity = isset($food_quantities[$index]) ? intval($food_quantities[$index]) : 1;

								if ($food_id > 0 && $quantity > 0) {
									$db->query("INSERT INTO reservation_foods(reservation_id, food_id, quantity) 
													VALUES('" . $reservation_id . "', '" . $food_id . "', '" . $quantity . "')");
								}
							}
						}

						// Commit transaction
						$db->commit();

						$msg = "<p style='padding: 15px; color: green; background: #eeffee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Reservasi berhasil! Kode reservasi Anda adalah $reserve_code. Harap diingat bahwa reservasi ini berlaku selama satu jam.</p>";
						header("Location: detail_order.php?id=$reservation_id");
						exit();
					} else {
						throw new Exception("Could not insert reservation");
					}
				} catch (Exception $e) {
					// Rollback transaction on error
					$db->rollback();
					$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Gagal membuat reservasi. Silakan coba lagi.</p>";
				}
			}
		} else {
			$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Data form tidak lengkap atau tipe data tidak valid.</p>";
		}
	}
}
?>

<!Doctype html>

<html lang="id">

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<meta name="description" content="Sistem Reservasi Restoran" />

<meta name="keywords" content="reservasi, restoran, makan, meja" />

<head>

	<title>MFORS - Reservasi</title>

	<link rel="stylesheet" href="css/main.css" />

	<script src="js/jquery.min.js"></script>

	<script src="js/myscript.js"></script>

	<style>
		.content-section {
			padding: 30px 0;
		}

		.section-title {
			font-size: 24px;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 2px solid #ddd;
			color: #333;
		}

		.form-container {
			max-width: 800px;
			margin: 0 auto;
		}

		.form-section {
			margin-bottom: 30px;
			padding: 20px;
			background: #f9f9f9;
			border-radius: 8px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		/* Tables styling */
		.table-options {
			display: flex;
			flex-wrap: wrap;
			gap: 15px;
			justify-content: center;
		}

		.table-option {
			flex: 0 0 calc(25% - 15px);
			padding: 15px;
			border: 2px solid #ddd;
			border-radius: 8px;
			cursor: pointer;
			text-align: center;
			transition: all 0.3s ease;
		}

		.table-option:hover {
			border-color: #aaa;
			transform: translateY(-3px);
		}

		.table-option.selected {
			background-color: #4CAF50;
			color: white;
			border-color: #4CAF50;
		}

		.table-option h4 {
			margin-top: 0;
			margin-bottom: 10px;
		}

		/* Food menu styling */
		.category-filter {
			margin-bottom: 20px;
			text-align: center;
		}

		.category-filter select {
			padding: 10px 15px;
			border-radius: 4px;
			border: 1px solid #ddd;
			font-size: 16px;
			width: 100%;
			max-width: 300px;
		}

		.food-container {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
			justify-content: center;
		}

		.food-item-card {
			flex: 0 0 calc(33.33% - 20px);
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 15px;
			transition: all 0.3s ease;
		}

		.food-item-card:hover {
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
			transform: translateY(-5px);
		}

		.food-item-card h4 {
			margin-top: 0;
			font-size: 18px;
			color: #333;
		}

		.food-description {
			font-size: 14px;
			color: #666;
			margin-bottom: 10px;
			height: 60px;
			overflow: hidden;
		}

		.food-price {
			font-weight: bold;
			color: #4CAF50;
			font-size: 16px;
			margin-bottom: 10px;
		}

		.food-controls {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.food-quantity {
			width: 60px;
			text-align: center;
			padding: 5px;
			border: 1px solid #ddd;
			border-radius: 4px;
		}

		.add-food-btn {
			background-color: #4CAF50;
			color: white;
			border: none;
			padding: 8px 12px;
			border-radius: 4px;
			cursor: pointer;
			flex-grow: 1;
			transition: background-color 0.3s;
		}

		.add-food-btn:hover {
			background-color: #3e8e41;
		}

		/* Selected items */
		.selected-items-section {
			margin-top: 30px;
			padding: 20px;
			background: #f5f5f5;
			border-radius: 8px;
		}

		.selected-items-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 15px;
		}

		.selected-items-header h3 {
			margin: 0;
		}

		.total-price {
			font-weight: bold;
			font-size: 18px;
			color: #4CAF50;
		}

		.selected-item {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 10px 0;
			border-bottom: 1px solid #ddd;
		}

		.selected-item:last-child {
			border-bottom: none;
		}

		.selected-item-info {
			flex-grow: 1;
		}

		.selected-item-name {
			font-weight: bold;
			margin-bottom: 5px;
		}

		.selected-item-price {
			color: #666;
		}

		.remove-food-btn {
			background-color: #f44336;
			color: white;
			border: none;
			padding: 5px 10px;
			border-radius: 4px;
			cursor: pointer;
		}

		/* Payment method */
		.payment-methods {
			display: flex;
			gap: 15px;
			margin-top: 10px;
		}

		.payment-method {
			flex: 1;
			padding: 15px;
			border: 2px solid #ddd;
			border-radius: 8px;
			cursor: pointer;
			text-align: center;
			transition: all 0.3s ease;
		}

		.payment-method:hover {
			border-color: #aaa;
		}

		.payment-method.selected {
			background-color: #4CAF50;
			color: white;
			border-color: #4CAF50;
		}

		/* Form fields */
		.form-row {
			display: flex;
			flex-wrap: wrap;
			gap: 15px;
			margin-bottom: 15px;
		}

		.form-group {
			flex: 1 0 calc(50% - 15px);
		}

		.form-group label {
			display: block;
			margin-bottom: 5px;
			font-weight: bold;
		}

		.form-group input,
		.form-group textarea,
		.form-group select {
			width: 100%;
			padding: 10px;
			border: 1px solid #ddd;
			border-radius: 4px;
			font-size: 16px;
		}

		.form-group textarea {
			min-height: 100px;
		}

		/* Submit button */
		.submit-btn {
			background-color: #4CAF50;
			color: white;
			border: none;
			padding: 15px 30px;
			font-size: 18px;
			border-radius: 4px;
			cursor: pointer;
			width: 100%;
			margin-top: 20px;
			transition: background-color 0.3s;
		}

		.submit-btn:hover {
			background-color: #3e8e41;
		}

		/* Responsive adjustments */
		@media screen and (max-width: 768px) {
			.table-option {
				flex: 0 0 calc(50% - 15px);
			}

			.food-item-card {
				flex: 0 0 calc(50% - 20px);
			}

			.form-group {
				flex: 1 0 100%;
			}
		}

		@media screen and (max-width: 480px) {

			.table-option,
			.food-item-card {
				flex: 0 0 100%;
			}
		}
	</style>

</head>

<body>

	<?php require "includes/header.php"; ?>

	<div class="parallax" onclick="remove_class()">

		<div class="parallax_head">

			<h2>Reservasi</h2>
			<h3>Tempat & Makanan</h3>

		</div>

	</div>

	<div class="content" onclick="remove_class()">

		<div class="inner_content">

			<div class="form-container">
				<?php echo "<br/>" . $msg; ?>

				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="hr_book_form">

					<div class="form-section">
						<h2 class="section-title">Informasi Pribadi</h2>
						<div class="form-row">
							<div class="form-group">
								<label>Jumlah Tamu</label>
								<input type="number" placeholder="Jumlah tamu" min="1" name="guest" id="guest" required>
							</div>

							<div class="form-group">
								<label>Email</label>
								<input type="email" name="email" placeholder="Alamat email Anda" required>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group">
								<label>Nomor Telepon</label>
								<input type="text" name="phone" placeholder="Nomor telepon Anda" required>
							</div>

							<div class="form-group">
								<label>Tanggal</label>
								<input type="date" name="date_res" required>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group">
								<label>Waktu</label>
								<input type="time" name="time" required>
							</div>

							<div class="form-group">
								<label>Catatan Khusus</label>
								<textarea name="suggestions" placeholder="Permintaan atau catatan khusus"></textarea>
							</div>
						</div>
					</div>

					<!-- Table Selection -->
					<div class="form-section">
						<h2 class="section-title">Pilih Meja</h2>

						<?php if (count($tables) > 0): ?>
							<div class="table-options">
								<?php foreach ($tables as $table): ?>
									<div class="table-option" data-table-id="<?php echo $table['id']; ?>">
										<h4>Meja #<?php echo $table['table_number']; ?></h4>
										<p>Kapasitas: <?php echo $table['capacity']; ?> orang</p>
										<p>Lokasi: <?php echo $table['location']; ?></p>
									</div>
								<?php endforeach; ?>
							</div>
							<input type="hidden" name="table_id" id="selected_table_id" required>
						<?php else: ?>
							<p>Tidak ada meja tersedia. Silakan hubungi restoran.</p>
						<?php endif; ?>
					</div>

					<!-- Food Menu Selection -->
					<div class="form-section">
						<h2 class="section-title">Pilih Menu Makanan</h2>

						<div class="category-filter">
							<select id="category-dropdown" onchange="filterFoodByCategory()">
								<option value="">Semua Kategori</option>
								<?php foreach ($food_categories as $category): ?>
									<option value="<?php echo $category; ?>"><?php echo $category; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="food-container">
							<?php foreach ($food_items as $item): ?>
								<div class="food-item-card" data-category="<?php echo $item['food_category']; ?>">
									<h4><?php echo $item['food_name']; ?></h4>
									<div class="food-description"><?php echo $item['food_description']; ?></div>
									<div class="food-price">Rp. <?php echo number_format($item['food_price'], 0, ',', '.'); ?></div>
									<div class="food-controls">
										<input type="number" class="food-quantity" min="1" value="1">
										<button type="button" class="add-food-btn"
											onclick="addFoodItem(<?php echo $item['id']; ?>, '<?php echo $item['food_name']; ?>', '<?php echo $item['food_price']; ?>', this)">Tambah</button>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="selected-items-section">
							<div class="selected-items-header">
								<h3>Menu Yang Dipilih</h3>
								<div class="total-price">Total: Rp. <span id="total-price-amount">0</span></div>
							</div>
							<div id="selected-items-container">
								<!-- Selected food items will be added here dynamically -->
								<p id="no-items-message">Belum ada menu yang dipilih.</p>
							</div>
						</div>
					</div>

					<!-- Payment Method -->
					<div class="form-section">
						<h2 class="section-title">Metode Pembayaran</h2>
						<div class="payment-methods">
							<div class="payment-method" data-method="kasir" onclick="selectPaymentMethod(this)">
								<h4>Bayar di Kasir</h4>
								<p>Pembayaran dilakukan saat tiba di restoran</p>
							</div>
							<div class="payment-method" data-method="transfer" onclick="selectPaymentMethod(this)">
								<h4>Transfer Bank</h4>
								<p>Pembayaran melalui transfer bank</p>
							</div>
						</div>
						<input type="hidden" name="payment_method" id="payment_method_input" required>
					</div>

					<input type="submit" class="submit-btn" name="submit" value="BUAT RESERVASI" />

				</form>
			</div>

		</div>

	</div>

	<div class="content" onclick="remove_class()">

		<div class="inner_content">

			<div class="contact">

				<div class="left">

					<h3>LOKASI</h3>
					<p>Buk New Site, New Campus</p>
					<p>Kano State</p>

				</div>

				<div class="left">

					<h3>KONTAK</h3>
					<p>08054645432, 07086898709</p>
					<p>Website@gmail.com</p>

				</div>

				<p class="left"></p>

				<div class="icon_holder">

					<a href="#"><img src="image/icons/Facebook.png" alt="image/icons/Facebook.png" /></a>
					<a href="#"><img src="image/icons/Google+.png" alt="image/icons/Google+.png" /></a>
					<a href="#"><img src="image/icons/Twitter.png" alt="image/icons/Twitter.png" /></a>

				</div>

			</div>

		</div>

	</div>

	<div class="footer_parallax" onclick="remove_class()">

		<div class="on_footer_parallax">

			<p>&copy; <?php echo strftime("%Y", time()); ?> <span>MyRestaurant</span>. Hak Cipta Dilindungi</p>

		</div>

	</div>

	<script>
		// Table selection
		document.querySelectorAll('.table-option').forEach(function(tableOption) {
			tableOption.addEventListener('click', function() {
				// Remove selected class from all options
				document.querySelectorAll('.table-option').forEach(function(option) {
					option.classList.remove('selected');
				});

				// Add selected class to clicked option
				this.classList.add('selected');

				// Update hidden input with selected table id
				document.getElementById('selected_table_id').value = this.getAttribute('data-table-id');
			});
		});

		// Payment method selection
		function selectPaymentMethod(element) {
			// Remove selected class from all options
			document.querySelectorAll('.payment-method').forEach(function(option) {
				option.classList.remove('selected');
			});

			// Add selected class to clicked option
			element.classList.add('selected');

			// Update hidden input with selected payment method
			document.getElementById('payment_method_input').value = element.getAttribute('data-method');
		}

		// Food menu selection
		var selectedItems = {};
		var totalPrice = 0;

		function addFoodItem(foodId, foodName, foodPrice, button) {
			var quantityInput = button.parentNode.querySelector('.food-quantity');
			var quantity = parseInt(quantityInput.value);

			if (quantity <= 0) {
				alert("Masukkan jumlah yang valid");
				return;
			}

			// Convert price string to number
			var numPrice = parseInt(foodPrice.replace(/[^0-9]/g, ''));

			// Update or add to selected items
			if (selectedItems[foodId]) {
				// If item already exists, update quantity
				selectedItems[foodId].quantity += quantity;
				totalPrice += (numPrice * quantity);
				updateSelectedItemDisplay(foodId);
			} else {
				// Add new item
				selectedItems[foodId] = {
					id: foodId,
					name: foodName,
					price: numPrice,
					quantity: quantity
				};
				totalPrice += (numPrice * quantity);
				addSelectedItemDisplay(selectedItems[foodId]);
			}

			// Update total price display
			document.getElementById('total-price-amount').textContent = formatPrice(totalPrice);

			// Reset quantity input
			quantityInput.value = 1;

			// Hide "no items" message if items exist
			document.getElementById('no-items-message').style.display = 'none';
		}

		function addSelectedItemDisplay(item) {
			var container = document.getElementById('selected-items-container');
			var itemElement = document.createElement('div');
			itemElement.className = 'selected-item';
			itemElement.id = 'selected-item-' + item.id;

			itemElement.innerHTML = `
			<div class="selected-item-info">
				<div class="selected-item-name">${item.name}</div>
				<div class="selected-item-price">${item.quantity} x Rp. ${formatPrice(item.price)} = Rp. ${formatPrice(item.price * item.quantity)}</div>
			</div>
			<input type="hidden" name="food_items[]" value="${item.id}">
			<input type="hidden" name="food_quantities[]" value="${item.quantity}">
			<button type="button" class="remove-food-btn" onclick="removeFoodItem(${item.id})">Hapus</button>
		`;

			container.appendChild(itemElement);
		}

		function updateSelectedItemDisplay(foodId) {
			var item = selectedItems[foodId];
			var itemElement = document.getElementById('selected-item-' + foodId);

			if (itemElement) {
				var itemPrice = item.price * item.quantity;
				itemElement.querySelector('.selected-item-price').textContent =
					`${item.quantity} x Rp. ${formatPrice(item.price)} = Rp. ${formatPrice(itemPrice)}`;
				itemElement.querySelector('input[name="food_quantities[]"]').value = item.quantity;
			}
		}

		function removeFoodItem(foodId) {
			// Subtract price from total
			totalPrice -= (selectedItems[foodId].price * selectedItems[foodId].quantity);
			document.getElementById('total-price-amount').textContent = formatPrice(totalPrice);

			// Remove from object
			delete selectedItems[foodId];

			// Remove from display
			var itemElement = document.getElementById('selected-item-' + foodId);
			if (itemElement) {
				itemElement.parentNode.removeChild(itemElement);
			}

			// Show "no items" message if no items left
			if (Object.keys(selectedItems).length === 0) {
				document.getElementById('no-items-message').style.display = 'block';
			}
		}

		function filterFoodByCategory() {
			var category = document.getElementById('category-dropdown').value;
			var foodItems = document.querySelectorAll('.food-item-card');

			foodItems.forEach(function(item) {
				if (!category || item.getAttribute('data-category') === category) {
					item.style.display = 'block';
				} else {
					item.style.display = 'none';
				}
			});
		}

		function formatPrice(price) {
			return new Intl.NumberFormat('id-ID').format(price);
		}
	</script>

</body>

</html>