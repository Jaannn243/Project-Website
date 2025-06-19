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

$sql = "SELECT * from author where nickname='$nickname'";
$query = $koneksi->query($sql);
$data = $query->fetch_array();
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
    <style>
        .tittle {
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
            background-color: rgb(255, 102, 0);
            /* Warna biru untuk garis */
            margin: 10px auto;
            /* Mengatur margin agar garis berada di tengah */
        }

        /* Container untuk setiap artikel */
        .article-container {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header untuk artikel (judul, penulis, kategori, status) */
        .article-header {
            margin-bottom: 15px;
        }

        .article-header h3 {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .article-header p {
            font-size: 14px;
            color: #555;
        }

        /* Isi artikel (secuil artikel) */
        .article-content {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }

        /* Tombol Aksi (Edit, Hapus, Selengkapnya) */
        .article-actions {
            display: flex;
            justify-content: space-between;
        }

        .article-actions .button.small {
            padding: 0px 10px;
            background-color: rgb(255, 255, 255);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .article-actions .button.small:hover {
            background-color: rgb(255, 57, 57);
        }

        .article-image {
            margin-bottom: 15px;
            text-align: center;
        }

        .article-img {
            max-width: 100%;
            /* Agar gambar responsif */
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <style>
        /* Modal Style */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
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

    if ($result_categories->num_rows > 0) {
        while ($row = $result_categories->fetch_assoc()) {
            $categories[] = $row['category_name'];
            $article_counts[] = $row['article_count'];
        }
    }

    // Query untuk mengambil artikel, penulis, kategori
    if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category_id = $_GET['category'];
    $sql_articles = "
    SELECT a.id, a.title, a.content, a.status, a.date, a.picture,
           c.name AS category_name,
           u.nickname AS author_name
    FROM article a
    LEFT JOIN article_category ac ON a.id = ac.article_id
    LEFT JOIN category c ON ac.category_id = c.id
    LEFT JOIN article_author aa ON a.id = aa.article_id
    LEFT JOIN author u ON aa.author_id = u.id
    WHERE c.id = $category_id
    ORDER BY RAND() LIMIT 10";
    } else{
        $sql_articles = "
    SELECT a.id, a.title, a.content, a.status, a.date, a.picture,
           c.name AS category_name,
           u.nickname AS author_name
    FROM article a
    LEFT JOIN article_category ac ON a.id = ac.article_id
    LEFT JOIN category c ON ac.category_id = c.id
    LEFT JOIN article_author aa ON a.id = aa.article_id
    LEFT JOIN author u ON aa.author_id = u.id
    ORDER BY RAND() LIMIT 10";
    }
    $result_articles = $koneksi->query($sql_articles);
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

                <form method="GET" action="artikel.php">
                    <label for="category">Pilih Kategori:</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <?php
                        // Ambil daftar kategori dari database
                        $sql_categories = "SELECT id, name FROM category";
                        $result_categories = $koneksi->query($sql_categories);

                        // Tampilkan kategori dalam dropdown
                        while ($row = $result_categories->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '" ' . (isset($_GET['category']) && $_GET['category'] == $row['id'] ? 'selected' : '') . '>' . htmlspecialchars($row['name']) . '</option>';
                        }
                        ?>
                    </select>
                </form>

                <!-- Artikel List -->
                <section>
                    <h2 class="tittle">Daftar Artikel</h2>

                    <?php while ($row = $result_articles->fetch_assoc()) { ?>
                        <div class="article-container">
                            <div class="article-header">
                                <h3><?= htmlspecialchars($row['title']); ?></h3>
                                <div class="article-image">
                                    <!-- Menampilkan gambar jika ada -->
                                    <?php if (!empty($row['picture'])): ?>
                                        <img src="../assets/image/<?= htmlspecialchars($row['picture']); ?>" alt="<?= htmlspecialchars($row['title']); ?>" class="article-img">
                                    <?php else: ?>
                                        <p>Tidak ada gambar untuk artikel ini.</p>
                                    <?php endif; ?>
                                </div>
                                <p><strong>Penulis:</strong> <?= htmlspecialchars($row['author_name'] ?? 'Tidak Diketahui'); ?></p>
                                <p><strong>Kategori:</strong> <?= htmlspecialchars($row['category_name'] ?? 'Tidak Diketahui'); ?></p>
                                <p><strong>Status:</strong> <?= htmlspecialchars($row['status']); ?></p>
                                <p><strong>Tanggal Dibuat:</strong> <?= date("d-m-Y", strtotime($row['date'])); ?></p>
                            </div>

                            <div class="article-content">
                                <p><?= substr(htmlspecialchars($row['content']), 0, 100); ?>...</p>
                            </div>

                            <div class="article-actions">
                                <a href="edit_artikel.php?edit=<?= $row['id']; ?>" class="button">Edit</a>
                                <button class="button" onclick="openModal(<?= $row['id']; ?>)">Hapus</button>
                                <a href="article_detail.php?id=<?= $row['id']; ?>" class="button">Selengkapnya</a>
                            </div>
                        </div>
                    <?php } ?>
                </section>

            </div>
        </div>

        <!-- Modal Konfirmasi -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Apakah Anda Yakin Ingin Menghapus Artikel Ini?</h3>
                <button id="confirmDelete" class="button">Ya</button>
                <button class="button" onclick="closeModal()">Tidak</button>
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

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var articleIdToDelete = null;

        // Fungsi untuk membuka modal
        function openModal(articleId) {
            articleIdToDelete = articleId;
            modal.style.display = "block";
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Event listener untuk tombol konfirmasi hapus
        document.getElementById("confirmDelete").onclick = function() {
            window.location.href = "delete_article.php?id=" + articleIdToDelete;
            closeModal();
        }

        // Menutup modal ketika klik di luar modal
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Menutup modal dengan tombol close
        span.onclick = function() {
            closeModal();
        }
    </script>


</body>

</html>