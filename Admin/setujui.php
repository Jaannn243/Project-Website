<!DOCTYPE HTML>
<html>

<head>
    <title>KataKita || Setujui Artikel</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        /* Styling untuk artikel */
        .article-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }

        .article-card {
            width: 48%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .article-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .article-card-content {
            padding: 20px;
        }

        .article-card h3 {
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .article-card p {
            font-size: 1em;
            color: #666;
            margin-bottom: 15px;
        }

        .article-card .date {
            font-size: 0.9em;
            color: #999;
        }

        .article-card .status {
            margin-top: 10px;
            font-size: 1.1em;
            font-weight: bold;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        .button-group button {
            padding: 10px 20px;
            border: none;
            background-color:rgb(255, 255, 255);
            color: white;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
        }

        .button-group button:hover {
            background-color: rgb(255, 164, 164);
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

    // Ambil artikel dengan status 'Menunggu Persetujuan'
    $sql_articles = "SELECT * FROM article WHERE status = 'Menunggu Persetujuan'";
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
                </header>

                <!-- Content -->
                <section>
                    <header class="major">
                        <h2>Setujui Artikel</h2>
                    </header>

                    <!-- Tampilkan artikel yang menunggu persetujuan -->
                    <div class="article-container">
                        <?php while ($row = $result_articles->fetch_assoc()) { ?>
                            <div class="article-card">
                                <img src="../assets/image/<?= htmlspecialchars($row['picture']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
                                <div class="article-card-content">
                                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                                    <p><?= substr(htmlspecialchars($row['content']), 0, 150); ?>...</p>
                                    <p class="date"><?= htmlspecialchars($row['date']); ?></p>

                                    <div class="status">Status: <?= htmlspecialchars($row['status']); ?></div>

                                    <!-- Tombol Setujui dan Tolak -->
                                    <div class="button-group">
                                        <button onclick="updateStatus(<?= $row['id']; ?>, 'Disetujui')">Setujui</button>
                                        <button onclick="updateStatus(<?= $row['id']; ?>, 'Ditolak')">Tolak</button>
                                    </div>
                                </div>
                            </div>
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
        // Fungsi untuk update status artikel menggunakan AJAX
        function updateStatus(articleId, status) {
            $.ajax({
                url: 'update_article_status.php',
                type: 'POST',
                data: {
                    id: articleId,
                    status: status
                },
                success: function(response) {
                    alert(response); // Menampilkan pesan berhasil/tidak
                    location.reload(); // Reload halaman untuk memperbarui status artikel
                },
                error: function() {
                    alert("Terjadi kesalahan saat memperbarui status.");
                }
            });
        }
    </script>

</body>

</html>