<?php
// Mulai session untuk memastikan user yang mengakses memiliki hak akses
session_start();

// Include koneksi ke database
include 'connection.php';

// Cek apakah user sudah login dan memiliki hak akses
if ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Author') {
    echo "Anda tidak memiliki izin untuk menghapus artikel.";
    exit();
}

// Pastikan ada ID yang dikirimkan lewat URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Pastikan ID valid, misalnya harus angka
    if (is_numeric($id)) {
        // Query untuk menghapus artikel berdasarkan ID
        $sql = "DELETE FROM article WHERE id = $id";

        // Eksekusi query
        if ($koneksi->query($sql) === TRUE) {
            echo "Artikel berhasil dihapus.";
            header("Location: dashboard.php"); // Redirect kembali ke dashboard setelah penghapusan
        } else {
            echo "Terjadi kesalahan: " . $koneksi->error;
        }
    } else {
        echo "ID artikel tidak valid.";
    }
} else {
    echo "ID artikel tidak ditemukan.";
}
?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Delete Article</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<body class="is-preload">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main -->
        <div id="main">
            <div class="inner">

                <!-- Header -->
                <header id="header">
                    <a href="index.html" class="logo"><strong>Editorial</strong> by HTML5 UP</a>
                    <ul class="icons">
                        <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
                        <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
                        <li><a href="#" class="icon brands fa-snapchat-ghost"><span class="label">Snapchat</span></a></li>
                        <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
                        <li><a href="#" class="icon brands fa-medium-m"><span class="label">Medium</span></a></li>
                    </ul>
                </header>

                <!-- Section untuk Konfirmasi Hapus -->
                <section>
                    <header class="major">
                        <h2>Delete Article</h2>
                    </header>

                    <p>Are you sure you want to delete the article titled: <strong><?= htmlspecialchars($article['title']); ?></strong>?</p>

                    <form method="POST" action="hapus_artikel.php?delete=<?= $article_id; ?>">
                        <ul class="actions">
                            <li><input type="submit" name="confirm_delete" value="Yes, Delete" class="button primary" /></li>
                            <li><a href="dashboard.php" class="button">Cancel</a></li>
                        </ul>
                    </form>
                </section>

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
