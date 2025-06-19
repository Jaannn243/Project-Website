<?php
session_start();
include "connection.php";

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nickname'])) {
    header("Location: login.php");
    exit();
}

$nickname = $_SESSION['nickname'];
$level = $_SESSION['level'];

// Ambil data pengguna
$sql_user = "SELECT nickname, email FROM author WHERE nickname = '$nickname'";
$result_user = $koneksi->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $nickname = $user['nickname'];
    $email = $user['email'];
}

// Logic untuk Admin dan Author
if ($level == 'Admin') {
    // Ambil data user
	if ($result_user->num_rows > 0) {
		$user = $result_user->fetch_assoc();
	}

	$sql = "SELECT * from author where nickname='$nickname'";
	$query = $koneksi->query($sql);
	$data = $query->fetch_array();

	// Query untuk mendapatkan jumlah artikel per kategori
	$sql_categories = "SELECT c.name AS category_name, COUNT(a.id) AS article_count
                   FROM category c
                   LEFT JOIN article_category ac ON c.id = ac.category_id
                   LEFT JOIN article a ON ac.article_id = a.id
                   GROUP BY c.id";

	$result_categories = $koneksi->query($sql_categories);

	$categories = [];
	$article_counts = [];

	// Menghitung jumlah artikel berdasarkan status
	$sql_status = "SELECT status, COUNT(*) AS total FROM article GROUP BY status";
	$result_status = $koneksi->query($sql_status);

	$status_counts = [
		'Menunggu Persetujuan' => 0,
		'Diterima' => 0,
		'Ditolak' => 0
	];

	// Mengambil jumlah artikel per status
	while ($row = $result_status->fetch_assoc()) {
		$status_counts[$row['status']] = $row['total'];
	}


	// Ambil jumlah semua pengguna
	$sql_all_users = "SELECT COUNT(*) AS total_users FROM author";
	$result_all_users = $koneksi->query($sql_all_users);
	$total_users = $result_all_users->fetch_assoc()['total_users'];

	// Ambil jumlah Author
	$sql_authors = "SELECT COUNT(*) AS total_authors FROM author WHERE level = 'Author'";
	$result_authors = $koneksi->query($sql_authors);
	$total_authors = $result_authors->fetch_assoc()['total_authors'];

	// Ambil jumlah Admin
	$sql_admins = "SELECT COUNT(*) AS total_admins FROM author WHERE level = 'Admin'";
	$result_admins = $koneksi->query($sql_admins);
	$total_admins = $result_admins->fetch_assoc()['total_admins'];


	if ($result_categories->num_rows > 0) {
		while ($row = $result_categories->fetch_assoc()) {
			$categories[] = $row['category_name'];
			$article_counts[] = $row['article_count'];
		}
	}
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>KataKita</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

	<!-- Tambahkan CSS di dalam tag <style> -->
	<style>
		.chart-container {
			width: 70%;
			/* Menentukan lebar container */
			margin: 0 auto;
			/* Menjaga agar container terpusat di halaman */
			padding: 20px;
			/* Memberikan padding di sekitar chart */
			border: 2px solid #ddd;
			/* Menambahkan border di sekitar container */
			border-radius: 10px;
			/* Memberikan sudut yang lebih halus pada container */
			background-color: #f9f9f9;
			/* Warna latar belakang yang ringan */
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			/* Memberikan bayangan ringan */
		}

		.stats-container {
			display: flex;
			justify-content: space-between;
			margin-top: 30px;
			margin-bottom: 30px;
		}

		.stat-box {
			width: 30%;
			background-color: #f4f4f9;
			padding: 10px;
			text-align: center;
			border-radius: 2px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.stat-box h3 {
			font-size: 16px;
			color: #333;
		}

		.stat-box p {
			font-size: 20px;
			font-weight: bold;
			color: #2d92ff;
		}

		.stat-icon {
			font-size: 40px;
			color: #2d92ff;
			margin-bottom: 15px;
		}
	</style>
	<style>
		.tittle{
			font-size: 24px;
			font-weight: bold;
			color: #333;
			text-align: center;
			margin-bottom: 15px;
			position: relative;
		}
		.tittle::after {
			content: '';
			display: block;
			width: 50%;
			height: 2px;
			background-color: rgb(255,102,0);
			/* Warna biru untuk garis */
			margin: 10px auto;
			/* Mengatur margin agar garis berada di tengah */
		}

		/* Container untuk setiap bagian (Pengguna dan Status Artikel) */
		.section-container {
			margin-top: 30px;
			margin-bottom: 30px;
		}

		/* Styling untuk judul */
		.section-title {
			font-size: 24px;
			font-weight: bold;
			color: #333;
			text-align: center;
			margin-bottom: 15px;
			position: relative;
		}

		/* Garis pemisah di bawah judul */
		.section-title::after {
			content: '';
			display: block;
			width: 50%;
			height: 2px;
			background-color: rgb(255, 102, 0);
			/* Warna biru untuk garis */
			margin: 10px auto;
			/* Mengatur margin agar garis berada di tengah */
		}

		/* Styling untuk stats-container */
		.stats-container {
			display: flex;
			justify-content: space-between;
		}

		/* Styling untuk setiap stat-box */
		.stat-box {
			width: 30%;
			background-color: #f4f4f9;
			padding: 20px;
			text-align: center;
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			position: relative;
		}

		/* Styling untuk ikon */
		.stat-icon {
			font-size: 40px;
			color: #2d92ff;
			margin-bottom: 15px;
		}

		.stat-box p {
			font-size: 24px;
			font-weight: bold;
			color: #2d92ff;
		}

		/* Warna untuk masing-masing container */
		.stat-box:nth-child(1) {
			background-color: #e0f7fa;
			/* Biru muda */
		}

		.stat-box:nth-child(2) {
			background-color: #c8e6c9;
			/* Hijau muda */
		}

		.stat-box:nth-child(3) {
			background-color: #ffe0b2;
			/* Kuning muda */
		}
	</style>
</head>

<body class="is-preload">
	<?php
	$nickname = $_SESSION['nickname'];
	$sql_user = "SELECT nickname, email FROM author WHERE nickname = '$nickname'";
	$result_user = $koneksi->query($sql_user);

	// Ambil data user
	if ($result_user->num_rows > 0) {
		$user = $result_user->fetch_assoc();
		$nickname = $user['nickname'];
		$email = $user['email'];
	}

	$sql = "SELECT * from author where nickname='$nickname'";
	$query = $koneksi->query($sql);
	$data = $query->fetch_array();

	// Query untuk mendapatkan jumlah artikel per kategori
	$sql_categories = "SELECT c.name AS category_name, COUNT(a.id) AS article_count
                   FROM category c
                   LEFT JOIN article_category ac ON c.id = ac.category_id
                   LEFT JOIN article a ON ac.article_id = a.id
                   GROUP BY c.id";

	$result_categories = $koneksi->query($sql_categories);

	$categories = [];
	$article_counts = [];

	// Menghitung jumlah artikel berdasarkan status
	$sql_status = "SELECT status, COUNT(*) AS total FROM article GROUP BY status";
	$result_status = $koneksi->query($sql_status);

	$status_counts = [
		'Menunggu Persetujuan' => 0,
		'Diterima' => 0,
		'Ditolak' => 0
	];

	// Mengambil jumlah artikel per status
	while ($row = $result_status->fetch_assoc()) {
		$status_counts[$row['status']] = $row['total'];
	}


	// Ambil jumlah semua pengguna
	$sql_all_users = "SELECT COUNT(*) AS total_users FROM author";
	$result_all_users = $koneksi->query($sql_all_users);
	$total_users = $result_all_users->fetch_assoc()['total_users'];

	// Ambil jumlah Author
	$sql_authors = "SELECT COUNT(*) AS total_authors FROM author WHERE level = 'Author'";
	$result_authors = $koneksi->query($sql_authors);
	$total_authors = $result_authors->fetch_assoc()['total_authors'];

	// Ambil jumlah Admin
	$sql_admins = "SELECT COUNT(*) AS total_admins FROM author WHERE level = 'Admin'";
	$result_admins = $koneksi->query($sql_admins);
	$total_admins = $result_admins->fetch_assoc()['total_admins'];


	if ($result_categories->num_rows > 0) {
		while ($row = $result_categories->fetch_assoc()) {
			$categories[] = $row['category_name'];
			$article_counts[] = $row['article_count'];
		}
	}
	?>

	<!-- Wrapper -->
	<div id="wrapper">

		<!-- Main -->
		<div id="main">
			<div class="inner">

				<!-- Header -->
				<header id="header">
					<a href="index.php" class="logo"><strong>KataKita</strong> || By Educational Community</a>
					<ul class="icons">
						<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon brands fa-snapchat-ghost"><span class="label">Snapchat</span></a></li>
						<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon brands fa-medium-m"><span class="label">Medium</span></a></li>
					</ul>
				</header>

				<!-- Dashboard Content -->
				<div class="content">
					<h3 class="tittle">Dashboard Admin</h3>

					<!-- Diagram Balok Kategori -->
					<div class="chart-container" style="width: 100%; height: 400px;">
						<canvas id="categoryChart"></canvas>
					</div>

					<script>
						// Ambil data PHP yang sudah disiapkan
						const categories = <?php echo json_encode($categories); ?>;
						const articleCounts = <?php echo json_encode($article_counts); ?>;

						// Membuat diagram balok
						const ctx = document.getElementById('categoryChart').getContext('2d');
						const categoryChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: categories, // Kategori yang diambil dari PHP
								datasets: [{
									label: 'Jumlah Artikel',
									data: articleCounts, // Jumlah artikel per kategori yang diambil dari PHP
									backgroundColor: 'rgba(54, 162, 235, 0.2)',
									borderColor: 'rgba(54, 162, 235, 1)',
									borderWidth: 1
								}]
							},
							options: {
								responsive: true,
								scales: {
									y: {
										beginAtZero: true
									}
								}
							}
						});
					</script>

					<!-- Total Pengguna Section -->
					<div class="section-container">
						<h3 class="section-title">Total Pengguna</h3>
						<div class="stats-container">
							<!-- Total Pengguna -->
							<div class="stat-box">
								<i class="fas fa-users stat-icon"></i>
								<p><?= $total_users; ?> Pengguna</p>
							</div>

							<!-- Total Author -->
							<div class="stat-box">
								<i class="fas fa-user stat-icon"></i>
								<p><?= $total_authors; ?> Author</p>
							</div>

							<!-- Total Admin -->
							<div class="stat-box">
								<i class="fas fa-user-shield stat-icon"></i>
								<p><?= $total_admins; ?> Admin</p>
							</div>
						</div>
					</div>

					<!-- Status Artikel Section -->
					<div class="section-container">
						<h3 class="section-title">Status Artikel</h3>
						<div class="stats-container">
							<!-- Menunggu Persetujuan -->
							<div class="stat-box">
								<i class="fas fa-clock stat-icon"></i>
								<p><?= $status_counts['Menunggu Persetujuan']; ?> Artikel</p>
							</div>

							<!-- Diterima -->
							<div class="stat-box">
								<i class="fas fa-check-circle stat-icon"></i>
								<p><?= $status_counts['Disetujui']; ?> Artikel</p>
							</div>

							<!-- Ditolak -->
							<div class="stat-box">
								<i class="fas fa-times-circle stat-icon"></i>
								<p><?= $status_counts['Ditolak']; ?> Artikel</p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>


		<!-- Sidebar -->
		<div id="sidebar">
			<div class="inner">

				<!-- Search -->
				<section id="search" class="alt">
					<form method="post" action="#">
						<input type="text" name="query" id="query" placeholder="Search" />
					</form>
				</section>

				<!-- Menu -->
				<nav id="menu">
					<header class="major">
						<h2>Menu</h2>
					</header>
					<ul>
						<li><a href="index.php">Dashboard Admin</a></li>
						<li><a href="Admin/setujui.php">Setujui Artikel</a></li>
						<li><a href="Admin/lihat_pengguna.php">Lihat Pengguna</a></li>
						<li><a href="Admin/kategori.php">Kategori</a></li>
						<li><a href="Admin/artikel.php">Artikel</a></li>
						<li>
							<span class="opener">Submenu</span>
							<ul>
								<li><a href="Admin/profile.php">Profile</a></li>
								<li><a href="Admin/logout.php">Logout</a></li>
							</ul>
						</li>
					</ul>
				</nav>

				<!-- Section -->
				<section>
					<header class="major">
						<h2>Get in touch</h2>
					</header>
					<p>Selamat datang di website kami <strong>KataKita</strong>, jangan lupa ketika anda membutuhkan artikel untuk membantu pekerjaan anda ataupun sedang dalam posisi Gabut, anda bisa mengunjungi website kami untuk membaca tentang sesuatu yang mungkin anda tertarik!.</p>
					<ul class="contact">
						<li class="icon solid fa-user"><a href="#"><?= htmlspecialchars($nickname); ?></a></li>
						<li class="icon solid fa-envelope"><a href="mailto:<?= htmlspecialchars($email); ?>"><?= htmlspecialchars($email); ?></a></li>
					</ul>
				</section>

				<!-- Footer -->
				<footer id="footer">
					<p class="copyright">&copy; Untitled. All rights reserved By Educational Community.</p>
				</footer>

			</div>
		</div>

	</div>

	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/browser.min.js"></script>
	<script src="assets/js/breakpoints.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>

</body>

</html>

<?php
} elseif ($level == 'Author') {
    // Author Content (Similar to your previous code for the Author's dashboard)
    // You can insert the author-specific content here, similar to the code you already had
?>
<!DOCTYPE HTML>
<html>

<head>
	<title>KataKita</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	<style>
		/* Styling untuk kontainer artikel */
		.article-container {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
			/* Jarak antar artikel */
			justify-content: space-between;
		}

		/* Styling untuk tiap artikel */
		.article-item {
			background-color: #fff;
			border: 1px solid #ddd;
			border-radius: 10px;
			padding: 20px;
			margin-bottom: 20px;
			width: 48%;
			/* Membuat artikel dalam 2 kolom */
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}

		.article-item .image {
			display: flex;
			justify-content: center;
			/* Memastikan gambar berada di tengah */
			align-items: center;
			/* Memastikan gambar berada di tengah secara vertikal */
			height: 200px;
			/* Menetapkan tinggi yang sama untuk semua gambar */
			overflow: hidden;
			/* Menyembunyikan gambar yang keluar dari container */
		}

		/* Styling untuk gambar artikel */
		.article-item .image img {
			width: 100%;
			height: 100%;
			max-height: 200px;
			object-fit: cover;
			border-radius: 5px;
		}

		/* Styling untuk judul artikel */
		.article-item h4 {
			font-size: 1.5em;
			margin-bottom: 10px;
		}

		/* Styling untuk bagian tanggal */
		.article-item .date {
			color: #888;
			font-size: 0.9em;
			margin-bottom: 10px;
		}

		/* Styling untuk bagian cuplikan artikel */
		.article-item .excerpt {
			font-size: 1em;
			color: #333;
			margin-bottom: 20px;
		}

		/* Styling untuk tombol 'Selengkapnya' */
		.article-item .actions .button {
			background-color: rgb(255, 255, 255);
			color: white;
			padding: 10px 15px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}

		.article-item .actions .button:hover {
			background-color: rgb(238, 83, 83);
		}

		/* Pemisah antar artikel */
		.article-separator {
			border: 1px solid #ddd;
			margin: 20px 0;
		}
	</style>
</head>

<body class="is-preload">
	<?php
	// Cek apakah ada kategori yang dipilih
	if (isset($_GET['category']) && !empty($_GET['category'])) {
		$category_id = $_GET['category'];
		// Ambil artikel berdasarkan kategori yang dipilih
		$sql = "SELECT a.id, a.title, a.content, a.status, a.date, a.picture, 
                   c.name AS category_name, u.nickname AS author_name
            FROM article a
            LEFT JOIN article_category ac ON a.id = ac.article_id
            LEFT JOIN category c ON ac.category_id = c.id
            LEFT JOIN article_author aa ON a.id = aa.article_id
            LEFT JOIN author u ON aa.author_id = u.id
            WHERE c.id = $category_id  /**Menambahkan filter berdasarkan kategori**/
            ORDER BY RAND() LIMIT 10"; // Mengambil artikel acak, terbatas 10 artikel
	} else {
		// Jika tidak ada kategori yang dipilih, tampilkan artikel acak
		$sql = "SELECT a.id, a.title, a.content, a.status, a.date, a.picture, 
                   c.name AS category_name, u.nickname AS author_name
            FROM article a
            LEFT JOIN article_category ac ON a.id = ac.article_id
            LEFT JOIN category c ON ac.category_id = c.id
            LEFT JOIN article_author aa ON a.id = aa.article_id
            LEFT JOIN author u ON aa.author_id = u.id
            ORDER BY RAND() LIMIT 10";
	}

	$result = $koneksi->query($sql);

	$nickname = $_SESSION['nickname'];
	$sql_user = "SELECT nickname, email FROM author WHERE nickname = '$nickname'";
	$result_user = $koneksi->query($sql_user);

	// Ambil data user
	if ($result_user->num_rows > 0) {
		$user = $result_user->fetch_assoc();
		$nickname = $user['nickname'];
		$email = $user['email'];
	}

	$sql = "SELECT * from author where nickname='$nickname'";
	$query = $koneksi->query($sql);
	$data = $query->fetch_array();


	?>
	<!-- Wrapper -->
	<div id="wrapper">

		<!-- Main -->
		<div id="main">
			<div class="inner">

				<!-- Header -->
				<header id="header">
					<a href="index.php" class="logo"><strong>KataKita</strong> || By Educational Community</a>
					<ul class="icons">
						<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon brands fa-snapchat-ghost"><span class="label">Snapchat</span></a></li>
						<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon brands fa-medium-m"><span class="label">Medium</span></a></li>
					</ul>
				</header>

				<form method="GET" action="index.php">
					<label for="category">Pilih Kategori:</label>
					<select name="category" id="category" onchange="this.form.submit()">
						<option value="">Semua Kategori</option>
						<?php
						// Query untuk mengambil kategori dari database
						$sql_categories = "SELECT id, name FROM category";
						$result_categories = $koneksi->query($sql_categories);

						// Menampilkan kategori dalam dropdown
						while ($row = $result_categories->fetch_assoc()) {
							echo '<option value="' . $row['id'] . '" ' . (isset($_GET['category']) && $_GET['category'] == $row['id'] ? 'selected' : '') . '>' . htmlspecialchars($row['name']) . '</option>';
						}
						?>
					</select>
				</form>

				<section id="artikel">
					<div class="article-container">
						<?php
						while ($article = $result->fetch_array()) {
						?>
							<div class="article-item">
								<h4><?php echo $article['title']; ?></h4>
								<div class="image">
									<img src="assets/image/<?php echo $article['picture']; ?>" alt="Image Artikel">
								</div>
								<p class="date"><?php echo $article['date']; ?></p>
								<p class="excerpt"><?php echo substr($article['content'], 0, 200); ?>...</p>
								<div class="actions">
									<a href="article_detail.php?id=<?php echo $article['id']; ?>" class="button">Selengkapnya</a>
								</div>
							</div>
							<hr class="article-separator"> <!-- Pemisah antara artikel -->
						<?php } ?>
					</div>
				</section>

			</div>
		</div>

		<!-- Sidebar -->
		<div id="sidebar">
			<div class="inner">

				<!-- Search -->
				<section id="search" class="alt">
					<form method="post" action="#">
						<input type="text" name="query" id="query" placeholder="Search" />
					</form>
				</section>

				<!-- Menu -->
				<nav id="menu">
					<header class="major">
						<h2>Menu</h2>
					</header>
					<ul>
						<li><a href="index.php">Homepage</a></li>
						<li><a href="User/dashboard.php">Artikel Saya</a></li>
						<li><a href="User/form_upload.php">Upload Artikel</a></li>
						<li><a href="User/status.php">Status</a></li>
						<li>
							<span class="opener">User Menu</span>
							<ul>
								<li><a href="User/profile.php">Profile</a></li>
								<li><a href="User/logout.php">Logout</a></li>
							</ul>
						</li>
					</ul>
				</nav>

				<!-- Section -->
				<section>
					<header class="major">
						<h2>Get in touch</h2>
					</header>
					<p>Selamat datang di website kami <strong>KataKita</strong>, jangan lupa ketika anda membutuhkan artikel untuk membantu pekerjaan anda ataupun sedang dalam posisi Gabut, anda bisa mengunjungi website kami untuk membaca tentang sesuatu yang mungkin anda tertarik!.</p>
					<ul class="contact">
						<li class="icon solid fa-user"><a href="#"><?= htmlspecialchars($nickname); ?></a></li>
						<li class="icon solid fa-envelope"><a href="mailto:<?= htmlspecialchars($email); ?>"><?= htmlspecialchars($email); ?></a></li>
					</ul>
				</section>

				<!-- Footer -->
				<footer id="footer">
					<p class="copyright">&copy; Untitled. All rights reserved By Educational Community.</p>
				</footer>

			</div>
		</div>

	</div>
	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/browser.min.js"></script>
	<script src="assets/js/breakpoints.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>

</body>

</html>
<?php
} else {
    // Redirect if level is not recognized
    header("Location: login.php");
    exit();
}
?>
