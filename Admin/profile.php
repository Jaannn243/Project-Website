<?php
session_start();
include 'connection.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nickname'])) {
    header('Location: login.php');
    exit();
}

$nickname = $_SESSION['nickname']; // Ambil nickname dari session

// Query untuk mengambil data user (nickname, email, foto, dll.)
$sql_user = "SELECT * FROM author WHERE nickname = '$nickname'";
$result_user = $koneksi->query($sql_user);

// Ambil data user
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $nickname = $user['nickname'];
    $email = $user['email'];
    $photo = $user['foto']; // Pastikan ada kolom 'foto' di tabel 'author'
    $password = $user['password']; // Ambil password lama
}

// Proses untuk mengubah password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifikasi password lama
    if ($old_password === $password) { // Tidak menggunakan password_verify()
        // Password lama valid, lanjutkan untuk mengganti password
        if ($new_password === $confirm_password) {
            // Update password di database
            $update_sql = "UPDATE author SET password = '$new_password' WHERE nickname = '$nickname'";

            if ($koneksi->query($update_sql) === TRUE) {
                echo "Password berhasil diperbarui.";
            } else {
                echo "Terjadi kesalahan saat memperbarui password: " . $koneksi->error;
            }
        } else {
            echo "Konfirmasi password tidak cocok.";
        }
    } else {
        echo "Password lama tidak valid.";
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Profile</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        /* Styling untuk container profil */
        .profile-container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-photo {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-photo img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .profile-details p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        .profile-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .profile-form input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: rgb(255, 255, 255);
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .profile-form input[type="submit"]:hover {
            background-color: rgb(255, 68, 68);
        }
    </style>
</head>

<body class="is-preload">

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

                <!-- Profile Section with Container -->
                <section>
                    <div class="profile-container">
                        <h2>Your Profile</h2>

                        <!-- Profile Photo -->
                        <div class="profile-photo">
                            <?php if ($photo): ?>
                                <img src="assets/image/<?= $photo ?>" alt="Profile Photo" />
                            <?php else: ?>
                                <img src="assets/image/avatar.jpg" alt="Default Avatar" />
                            <?php endif; ?>
                        </div>

                        <!-- Profile Details -->
                        <div class="profile-details">
                            <p><strong>Nickname:</strong> <?= htmlspecialchars($nickname); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
                        </div>

                        <!-- Form to Change Password -->
                        <form method="POST" action="" class="profile-form">
                            <h3>Change Password</h3>
                            <label for="old_password">Old Password:</label>
                            <input type="text" id="old_password" name="old_password" required /> <!-- Mengganti password input menjadi text -->

                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" required />

                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required />

                            <input type="submit" name="change_password" value="Change Password" />
                        </form>
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
                        <li><a href="index.php">Dashboard Admin</a></li>
						<li><a href="setujui.php">Setujui Artikel</a></li>
						<li><a href="lihat_pengguna.php">Lihat Pengguna</a></li>
						<li><a href="kategori.php">Kategori</a></li>
                        <li><a href="artikel.php">Artikel</a></li>
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
					<p>Selamat datang di website kami <strong>KataKita</strong>, jangan lupa ketika anda membutuhkan artikel untuk membantu pekerjaan anda ataupun sedang dalam posisi Gabut, anda bisa meengunjungi website kami untuk membaca tentang sesuatu yang mungkin anda tertarik!.</p>
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