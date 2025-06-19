<!DOCTYPE HTML>
<html>

<head>
	<title>Lihat Pengguna</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	<link rel="stylesheet" href="assets/css/font-awesome.min.css" />
	<style>
		/* Modal Style */
		.modal {
			display: none;
			position: fixed;
			z-index: 9999;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			text-align: center;
		}

		.modal-content {
			background-color: #fff;
			margin: 0 auto;
			padding: 20px;
			border: 1px solid #888;
			width: 80%;
			max-width: 500px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			position: relative;
			top: 50%;
			transform: translateY(-50%);
		}

		.close {
			color: #aaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}

		.close:hover,
		.close:focus {
			color: black;
			text-decoration: none;
			cursor: pointer;
		}
	</style>
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

	// Ambil data pengguna dengan level 'Author' saja
	$sql = "SELECT * FROM author WHERE level = 'Author'";
	$result = $koneksi->query($sql);
	?>

	<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Ambil data dari AJAX
		$id = $_POST['id'];
		$nickname = $_POST['nickname'];
		$email = $_POST['email'];

		// Query untuk memperbarui data pengguna
		$sql = "UPDATE author SET nickname = '$nickname', email = '$email' WHERE id = '$id'";

		if ($koneksi->query($sql) === TRUE) {
			echo "Pengguna berhasil diperbarui!";
		} else {
			echo "Error: " . $sql . "<br>" . $koneksi->error;
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
				</header>

				<!-- Content -->
				<section>
					<header class="major">
						<h2>Lihat Pengguna</h2>
					</header>

					<!-- Tabel Pengguna -->
					<table>
						<thead>
							<tr>
								<th>ID</th>
								<th>Nickname</th>
								<th>Email</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $result->fetch_assoc()) { ?>
								<tr>
									<td><?= htmlspecialchars($row['id']); ?></td>
									<td><?= htmlspecialchars($row['nickname']); ?></td>
									<td><?= htmlspecialchars($row['email']); ?></td>
									<td>
										<!-- Tombol Edit -->
										<button class="button small" id="editBtn" data-id="<?= $row['id']; ?>" data-nickname="<?= $row['nickname']; ?>" data-email="<?= $row['email']; ?>">Edit</button>
										<!-- Tombol Hapus -->
										<button class="button small" id="deleteBtn" data-id="<?= $row['id']; ?>">Hapus</button>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
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

	<!-- Modal Edit Pengguna -->
	<div id="editModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Edit Pengguna</h2>
			<form id="editUserForm">
				<input type="hidden" id="userId" name="id">
				<label for="nickname">Nickname:</label><br>
				<input type="text" id="nickname" name="nickname" required><br><br>
				<label for="email">Email:</label><br>
				<input type="email" id="email" name="email" required><br><br>
				<button type="button" class="button primary" id="submitEdit">Edit Pengguna</button>
				<button type="button" class="button secondary" id="cancelEdit">Cancel</button>
			</form>
		</div>
	</div>

	<!-- Modal Konfirmasi Hapus -->
	<div id="deleteModal" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2>Hapus Pengguna Ini?</h2>
			<p>Apakah Anda yakin ingin menghapus pengguna ini?</p>
			<button class="button primary" id="confirmDelete">Ya</button>
			<button class="button secondary" id="cancelDelete">Tidak</button>
		</div>
	</div>

	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/browser.min.js"></script>
	<script src="assets/js/breakpoints.min.js"></script>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>

	<script>
		// Modal functionality
		var modal = document.getElementById("editModal");
		var editBtns = document.querySelectorAll("#editBtn");
		var span = document.getElementsByClassName("close")[0];
		var cancelBtn = document.getElementById("cancelEdit");
		var submitBtn = document.getElementById("submitEdit");
		var userId = null;

		// Open the modal when the "Edit" button is clicked
		editBtns.forEach(function(button) {
			button.onclick = function() {
				var id = this.getAttribute('data-id');
				var nickname = this.getAttribute('data-nickname');
				var email = this.getAttribute('data-email');

				// Set the values in the modal form
				document.getElementById('userId').value = id;
				document.getElementById('nickname').value = nickname;
				document.getElementById('email').value = email;

				// Show the modal
				modal.style.display = "block";
			}
		});

		// Close the modal when the <span> (x) is clicked
		span.onclick = function() {
			modal.style.display = "none";
		}

		// Close the modal when "Cancel" button is clicked
		cancelBtn.onclick = function() {
			modal.style.display = "none";
		}

		// Close the modal if the user clicks outside of the modal content
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}

		// Handle the form submission with AJAX
		submitBtn.onclick = function() {
			var id = document.getElementById('userId').value;
			var nickname = document.getElementById('nickname').value;
			var email = document.getElementById('email').value;

			// Prepare AJAX request
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "lihat_pengguna.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			// Handle response
			xhr.onload = function() {
				if (xhr.status === 200) {
					alert("Pengguna berhasil diperbarui!");
					location.reload(); // Reload halaman setelah update
				} else {
					alert("Gagal memperbarui pengguna.");
				}
			};

			// Send data using AJAX
			xhr.send("id=" + id + "&nickname=" + nickname + "&email=" + email);

			// Close modal after submission
			modal.style.display = "none";
		}
	</script>

	<script>
		// Modal functionality
		var deleteModal = document.getElementById("deleteModal");
		var deleteBtns = document.querySelectorAll("#deleteBtn");
		var span = document.getElementsByClassName("close")[0];
		var cancelDelete = document.getElementById("cancelDelete");
		var confirmDelete = document.getElementById("confirmDelete");
		var userIdToDelete = null;

		// Open the delete modal when the "Hapus" button is clicked
		deleteBtns.forEach(function(button) {
			button.onclick = function() {
				userIdToDelete = this.getAttribute('data-id');
				deleteModal.style.display = "block"; // Tampilkan modal konfirmasi
			};
		});

		// Close the delete modal when "Tidak" button is clicked
		cancelDelete.onclick = function() {
			deleteModal.style.display = "none"; // Tutup modal
		};

		// Close the modal when the <span> (x) is clicked
		span.onclick = function() {
			deleteModal.style.display = "none"; // Tutup modal
		};

		// Close the modal if the user clicks outside of the modal content
		window.onclick = function(event) {
			if (event.target == deleteModal) {
				deleteModal.style.display = "none"; // Tutup modal
			}
		};

		// Handle the deletion when "Ya" is clicked
		confirmDelete.onclick = function() {
			if (userIdToDelete) {
				// Mengirimkan request AJAX untuk menghapus pengguna
				var xhr = new XMLHttpRequest();
				xhr.open("GET", "delete_user.php?id=" + userIdToDelete, true);
				xhr.onload = function() {
					if (xhr.status === 200) {
						alert("Pengguna berhasil dihapus!");
						location.reload(); // Reload halaman untuk memperbarui daftar pengguna
					} else {
						alert("Gagal menghapus pengguna.");
					}
				};
				xhr.send(); // Kirim request untuk menghapus
			}
			deleteModal.style.display = "none"; // Tutup modal setelah aksi
		};
	</script>

</body>

</html>