<?php
session_start();
require "includes/db.php";
require "includes/functions.php";

if (!isset($_SESSION['user'])) {
  header("location: logout.php");
  exit();
}

if (!isset($_GET['id'])) {
  header("location: food_list.php");
  exit();
}

$food_id = intval($_GET['id']);
$msg = "";

// Ambil data dari database
$data = $db->query("SELECT * FROM food WHERE id = $food_id");
if ($data->num_rows == 0) {
  echo "<p style='color:red;'>Data tidak ditemukan</p>";
  exit();
}
$row = $data->fetch_assoc();

// Proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['submit'])) {
    $cat = htmlentities($_POST['category'], ENT_QUOTES, 'UTF-8');
    $name = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
    $price = htmlentities($_POST['price'], ENT_QUOTES, 'UTF-8');
    $desc = htmlentities($_POST['desc'], ENT_QUOTES, 'UTF-8');

    if ($cat != "" && $name != "" && $price != "" && $desc != "") {
      $update = $db->query("UPDATE food SET 
                food_name = '$name',
                food_category = '$cat',
                food_price = '$price',
                food_description = '$desc'
                WHERE food_id = $food_id");

      if ($update) {
        // Jika ada file gambar diupload, update juga
        if (!empty($_FILES['file']['name'])) {
          $allowed_ext = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");
          $ext = explode(".", $_FILES['file']['name']);
          if (in_array(end($ext), $allowed_ext)) {
            $image_url = "../image/FoodPics/$food_id.jpg";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $image_url)) {
              $msg = "<p style='color:green;'>Berhasil diperbarui</p>";
            } else {
              $msg = "<p style='color:orange;'>Data diperbarui, tapi gagal upload gambar</p>";
            }
          } else {
            $msg = "<p style='color:red;'>Format gambar tidak valid</p>";
          }
        } else {
          $msg = "<p style='color:green;'>Berhasil diperbarui</p>";
        }
      } else {
        $msg = "<p style='color:red;'>Gagal memperbarui data</p>";
      }
    } else {
      $msg = "<p style='color:red;'>Form tidak lengkap</p>";
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Edit Menu</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet" />
  <link href="assets/css/demo.css" rel="stylesheet" />
  <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>

  <div class="wrapper">
    <div class="sidebar" data-color="#000" data-image="assets/img/sidebar-5.jpg">
      <?php require "includes/side_wrapper.php"; ?>
    </div>

    <div class="main-panel">
      <nav class="navbar navbar-default navbar-fixed" style="background: #4caf50;">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#" style="color: #fff;">EDIT FOOD</a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="logout.php" style="color: #fff;">Log out</a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="header">
                  <h4 class="title">Edit Menu</h4>
                </div>
                <div class="content">
                  <form method="post" action="" enctype="multipart/form-data">
                    <?php echo $msg; ?>

                    <div class="form-group">
                      <label>Kategori</label>
                      <select name="category" class="form-control" required>
                        <option value="">Pilih kategori</option>
                        <option value="breakfast" <?= $row['food_category'] == 'breakfast' ? 'selected' : '' ?>>
                          Breakfast</option>
                        <option value="lunch" <?= $row['food_category'] == 'lunch' ? 'selected' : '' ?>>Lunch</option>
                        <option value="dinner" <?= $row['food_category'] == 'dinner' ? 'selected' : '' ?>>Dinner
                        </option>
                        <option value="special" <?= $row['food_category'] == 'special' ? 'selected' : '' ?>>Special
                        </option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Nama Makanan</label>
                      <input type="text" name="name" class="form-control" value="<?= $row['food_name'] ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Harga</label>
                      <input type="text" name="price" class="form-control" value="<?= $row['food_price'] ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Deskripsi</label>
                      <textarea name="desc" class="form-control" required><?= $row['food_description'] ?></textarea>
                    </div>

                    <div class="form-group">
                      <label>Gambar Saat Ini:</label><br>
                      <img src="../image/FoodPics/<?= $row['food_id'] ?>.jpg" style="max-width:150px;"
                        alt="Food Image"><br><br>
                      <label for="file">Ganti Gambar (Opsional)</label>
                      <input type="file" name="file" id="file" class="form-control">
                    </div>

                    <a href="food_list.php" class="btn btn-default btn-fill">Kembali</a>
                    <input type="submit" name="submit" value="Update Menu" class="btn btn-info btn-fill pull-right" />
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <footer class="footer">
        <div class="container-fluid">
          <p class="copyright pull-right">
            &copy; <?php echo date("Y"); ?> <a href="index.php">Unique Restaurant</a>
          </p>
        </div>
      </footer>
    </div>
  </div>

  <script src="assets/js/jquery-1.10.2.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
  <script src="assets/js/chartist.min.js"></script>
  <script src="assets/js/bootstrap-notify.js"></script>
  <script src="assets/js/light-bootstrap-dashboard.js"></script>
  <script src="assets/js/demo.js"></script>
</body>

</html>