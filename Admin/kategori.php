<!DOCTYPE HTML>
<html>

<head>
    <title>KataKita - Kategori</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" /> <!-- Pastikan Font Awesome ada untuk tombol edit/hapus -->
    <style>
        /* Modal Style */
        .modal {
            display: none;
            /* Modal tidak terlihat secara default */
            position: fixed;
            z-index: 9999;
            /* Pastikan modal berada di atas elemen lainnya */
            left: 0;
            top: 0;
            width: 100%;
            /* Lebar 100% untuk menutupi seluruh layar */
            height: 100%;
            /* Tinggi 100% untuk menutupi seluruh layar */
            background-color: rgba(0, 0, 0, 0.5);
            /* Latar belakang gelap */
            padding-top: 0;
            /* Hilangkan padding-top yang menyebabkan modal terlalu ke bawah */
            text-align: center;
        }

        /* Modal Content */
        .modal-content {
            background-color: #fff;
            margin: 0 auto;
            /* Tengah secara horizontal */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            top: 50%;
            /* Posisi modal di tengah secara vertikal */
            transform: translateY(-50%);
            /* Benar-benar berada di tengah */
        }

        /* Close Button (X) */
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

    // Ambil data kategori
    $sql = "SELECT * FROM category";
    $result = $koneksi->query($sql);
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
                        <h2>Daftar Kategori</h2>
                    </header>

                    <!-- Tombol Tambahkan Kategori -->
                    <button class="button primary" id="openModal">Tambahkan Kategori</button>

                    <!-- Tabel Kategori -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']); ?></td>
                                    <td><?= htmlspecialchars($row['name']); ?></td>
                                    <td><?= htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <a href="edit_kategori.php?id=<?= $row['id']; ?>" class="button small">Edit</a>
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

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Hapus Kategori Ini?</h2>
            <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
            <button class="button primary" id="confirmDelete">Ya</button>
            <button class="button secondary" id="cancelDelete">Tidak</button>
        </div>
    </div>

    <!-- Modal untuk Tambah Kategori -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Tambah Kategori</h2>
            <form id="addCategoryForm">
                <label for="name">Nama Kategori:</label><br>
                <input type="text" id="name" name="name" required><br><br>
                <label for="description">Deskripsi:</label><br>
                <textarea id="description" name="description" rows="4" required></textarea><br><br>
                <button type="button" class="button primary" id="submitCategory">Tambah Kategori</button>
                <button type="button" class="button secondary" id="cancelBtn">Cancel</button>
            </form>
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
        var modal = document.getElementById("myModal");
        var deleteModal = document.getElementById("deleteModal");
        var btn = document.getElementById("openModal");
        var deleteBtns = document.querySelectorAll("#deleteBtn");
        var span = document.getElementsByClassName("close")[0];
        var cancelBtn = document.getElementById("cancelBtn");
        var cancelDelete = document.getElementById("cancelDelete");
        var confirmDelete = document.getElementById("confirmDelete");
        var deleteId = null;
        document.getElementById("submitCategory").addEventListener("click", function() {
            var name = document.getElementById("name").value;
            var description = document.getElementById("description").value;

            // Validasi input
            if (name.trim() === "" || description.trim() === "") {
                alert("Nama kategori dan deskripsi harus diisi.");
                return;
            }

            // Mengirimkan data menggunakan AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "tambah_kategori.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Kirim data ke server
            var data = "name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Kategori berhasil ditambahkan!");
                    modal.style.display = "none"; // Menutup modal
                    location.reload(); // Reload halaman untuk menampilkan kategori terbaru
                } else {
                    alert("Gagal menambah kategori.");
                }
            };
            xhr.send(data); // Kirim data ke server
        });

        // Open the modal when the "Tambahkan Kategori" button is clicked
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Open the delete modal when the "Hapus" button is clicked
        deleteBtns.forEach(function(button) {
            button.onclick = function() {
                deleteId = this.getAttribute('data-id'); // Set the id of the category to delete
                deleteModal.style.display = "block"; // Show the delete confirmation modal
            }
        });

        // Close the modal when the <span> (x) is clicked
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the delete modal when "Tidak" button is clicked
        cancelDelete.onclick = function() {
            deleteModal.style.display = "none"; // Close delete modal
        }

        // Close the modal when "Cancel" button is clicked
        cancelBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal if the user clicks outside of the modal content
        window.onclick = function(event) {
            if (event.target == modal || event.target == deleteModal) {
                modal.style.display = "none";
                deleteModal.style.display = "none";
            }
        }

        // Handle delete action when user clicks "Ya"
        confirmDelete.onclick = function() {
            if (deleteId) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "delete_kategori.php?id=" + deleteId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert("Kategori berhasil dihapus!");
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert("Gagal menghapus kategori.");
                    }
                };
                xhr.send();
            }
            deleteModal.style.display = "none"; // Close delete modal
        }
    </script>

</body>

</html>