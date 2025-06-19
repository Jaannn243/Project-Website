<!DOCTYPE HTML>
<html>

<head>
    <title>Status Artikel - KataKita</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <style>
        /* Styling untuk container artikel */
        .article-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            margin-top: 20px;
        }

        /* Styling untuk tiap artikel */
        .article-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: 48%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Styling untuk gambar artikel */
        .article-item .image img {
            width: 100%;
            height: auto;
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
            background-color: rgb(231, 206, 94);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .article-item .actions .button:hover {
            background-color: rgb(14, 13, 13);
        }

        /* Styling untuk status artikel */
        .article-status {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
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
    session_start();
    include "connection.php";
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

    if (!isset($_SESSION['nickname'])) {
        die("Anda belum login");
    }

    $nick = $_SESSION['nickname'];
    $sql_user = "SELECT nickname, email FROM author WHERE nickname = '$nick'";
    $result_user = $koneksi->query($sql_user);

    // Ambil data user
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $nick = $user['nickname'];
        $email = $user['email'];
    }

    // Ambil ID user yang sedang login
    $sql = "SELECT id FROM author WHERE nickname = '$nick'";
    $query = $koneksi->query($sql);
    $data = $query->fetch_array();
    $author_id = $data['id'];  // Menyimpan ID user yang sedang login
    ?>

    <div id="wrapper">
        <div id="main">
            <div class="inner">

                <!-- Header -->
                <header id="header">
                    <a href="index.php" class="logo"><strong>KataKita</strong> || By Educational Community</a>
                </header>

                <!-- Artikel -->
                <section id="articles">
                    <header class="major">
                        <h2>Artikel Anda</h2>
                    </header>

                    <h3>Artikel Menunggu Persetujuan</h3>
                    <div class="article-container">
                        <?php
                        // Mengambil artikel yang statusnya 'Menunggu Persetujuan' dan dimiliki oleh user yang login
                        $sql_waiting = "
                            SELECT a.*, aa.author_id 
                            FROM article a
                            JOIN article_author aa ON a.id = aa.article_id 
                            WHERE a.status = 'Menunggu Persetujuan' AND aa.author_id = '$author_id' 
                            ORDER BY a.date DESC";
                        $result_waiting = $koneksi->query($sql_waiting);
                        while ($article = $result_waiting->fetch_array()) {
                        ?>
                            <div class="article-item">
                                <h4><?php echo $article['title']; ?></h4>
                                <div class="image">
                                    <img src="../assets/image/<?php echo $article['picture']; ?>" alt="Image Artikel">
                                </div>
                                <p class="date"><?php echo $article['date']; ?></p>
                                <p class="excerpt"><?php echo substr($article['content'], 0, 200); ?>...</p>
                                <!-- Status Container -->
                                <div class="article-status">
                                    Status: Menunggu Persetujuan
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <h3>Artikel Disetujui</h3>
                    <div class="article-container">
                        <?php
                        // Mengambil artikel yang statusnya 'Disetujui' dan dimiliki oleh user yang login
                        $sql_approved = "
                            SELECT a.*, aa.author_id 
                            FROM article a
                            JOIN article_author aa ON a.id = aa.article_id 
                            WHERE a.status = 'Disetujui' AND aa.author_id = '$author_id' 
                            ORDER BY a.date DESC";
                        $result_approved = $koneksi->query($sql_approved);
                        while ($article = $result_approved->fetch_array()) {
                        ?>
                            <div class="article-item">
                                <h4><?php echo $article['title']; ?></h4>
                                <div class="image">
                                    <img src="../assets/image/<?php echo $article['picture']; ?>" alt="Image Artikel">
                                </div>
                                <p class="date"><?php echo $article['date']; ?></p>
                                <p class="excerpt"><?php echo substr($article['content'], 0, 200); ?>...</p>
                                <!-- Status Container -->
                                <div class="article-status">
                                    Status: Disetujui
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <h3>Artikel Ditolak</h3>
                    <div class="article-container">
                        <?php
                        // Mengambil artikel yang statusnya 'Menunggu Persetujuan' dan dimiliki oleh user yang login
                        $sql_waiting = "
                            SELECT a.*, aa.author_id 
                            FROM article a
                            JOIN article_author aa ON a.id = aa.article_id 
                            WHERE a.status = 'Ditolak' AND aa.author_id = '$author_id' 
                            ORDER BY a.date DESC";
                        $result_waiting = $koneksi->query($sql_waiting);
                        while ($article = $result_waiting->fetch_array()) {
                        ?>
                            <div class="article-item">
                                <h4><?php echo $article['title']; ?></h4>
                                <div class="image">
                                    <img src="../assets/image/<?php echo $article['picture']; ?>" alt="Image Artikel">
                                </div>
                                <p class="date"><?php echo $article['date']; ?></p>
                                <p class="excerpt"><?php echo substr($article['content'], 0, 200); ?>...</p>
                                <!-- Status Container -->
                                <div class="article-status">
                                    Status: Ditolak
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
                        <li><a href="../index.php">Homepage</a></li>
                        <li><a href="dashboard.php">Artikel Saya</a></li>
                        <li><a href="form_upload.php">Upload Artikel</a></li>
                        <li><a href="status.php">Status</a></li>
                        <li>
                            <span class="opener">User Menu</span>
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
                        <li class="icon solid fa-user"><a href="#"><?= htmlspecialchars($nick); ?></a></li>
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