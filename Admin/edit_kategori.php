<?php
include "connection.php";

// Cek apakah ada parameter id di URL
if (!isset($_GET['id'])) {
    die("ID kategori tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data kategori berdasarkan ID
$sql = "SELECT * FROM category WHERE id = '$id'";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $category = $result->fetch_assoc();
} else {
    die("Kategori tidak ditemukan.");
}

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Edit Kategori</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body class="is-preload">
    <?php
    session_start();
    include "connection.php";
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    if (!isset($_SESSION['nickname'])) {
        die("Anda belum login");
    }

    $nickname = $_SESSION['nickname'];
    $sql_user = "SELECT nickname, email FROM author WHERE nickname = '$nickname'";
    $result_user = $koneksi->query($sql_user);

    // Ambil data user
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $nickname = $user['nickname'];
        $email = $user['email'];
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
                </header>

                <!-- Content -->
                <section>
                    <header class="major">
                        <h2>Edit Kategori</h2>
                    </header>

                    <!-- Form Edit Kategori -->
                    <form action="update_kategori.php" method="POST">
                        <input type="hidden" name="id" value="<?= $category['id']; ?>"> <!-- Menyimpan ID kategori -->
                        
                        <label for="name">Nama Kategori:</label><br>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']); ?>" required><br><br>
                        
                        <label for="description">Deskripsi:</label><br>
                        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($category['description']); ?></textarea><br><br>

                        <button type="submit" class="button primary">Simpan Perubahan</button>
                        <a href="kategori.php" class="button secondary">Batal</a>
                    </form>
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
                        <li><a href="index.php">Dashboard Admin</a></li>
                        <li><a href="setujui.php">Setujui Artikel</a></li>
                        <li><a href="lihat_pengguna.php">Lihat Pengguna</a></li>
                        <li><a href="kategori.php">Kategori</a></li>
                        <li>
                            <span class="opener">Submenu</span>
                            <ul>
                                <li><a href="profile.php">Profile</a></li>
                                <li><a href="logout.php">Logout</a></li>
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
