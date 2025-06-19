<?php
session_start();
include 'connection.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nickname'])) {
    header('Location: login.php');
    exit();
}

$nickname = $_SESSION['nickname'];

// Cek apakah ada artikel yang perlu diedit
if (isset($_GET['edit'])) {
    $article_id = $_GET['edit'];

    // Ambil data artikel yang sesuai dengan id
    $sql = "SELECT * FROM article WHERE id = $article_id";
    $query = $koneksi->query($sql);
    $article = $query->fetch_assoc();

    // Jika artikel tidak ditemukan, redirect
    if (!$article) {
        header('Location: dashboard.php');
        exit();
    }
} else {
    header('Location: dashboard.php');
    exit();
}

// Proses jika form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $picture = $_POST['picture']; // Bisa diubah sesuai keinginan (misal dengan upload gambar)

    // Update artikel di database
    $update_sql = "UPDATE article SET title = '$title', content = '$content', picture = '$picture' WHERE id = $article_id";
    if ($koneksi->query($update_sql) === TRUE) {
        echo "Article updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating article: " . $koneksi->error;
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Edit Article</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body class="is-preload">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main -->
        <div id="main">
            <div class="inner">

                <!-- Header -->
                <header id="header">
                    <a href="../index.html" class="logo"><strong>KataKita</strong> || By Educational Community</a>
                    <ul class="icons">
                        <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
                        <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
                        <li><a href="#" class="icon brands fa-snapchat-ghost"><span class="label">Snapchat</span></a></li>
                        <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
                        <li><a href="#" class="icon brands fa-medium-m"><span class="label">Medium</span></a></li>
                    </ul>
                </header>

                <!-- Form Edit Artikel -->
                <section>
                    <header class="major">
                        <h2>Edit Article</h2>
                    </header>

                    <form method="POST" action="edit_artikel.php?edit=<?= $article_id; ?>">
                        <table>
                            <tr>
                                <td>Title</td>
                                <td><input type="text" name="title" value="<?= htmlspecialchars($article['title']); ?>" required /></td>
                            </tr>
                            <tr>
                                <td>Content</td>
                                <td><textarea name="content" required><?= htmlspecialchars($article['content']); ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Picture Filename</td>
                                <td><input type="text" name="picture" value="<?= htmlspecialchars($article['picture']); ?>" /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" value="Update Article" /></td>
                            </tr>
                        </table>
                    </form>

                    <!-- Link Kembali ke Dashboard -->
                    <ul class="actions">
                        <li><a href="dashboard.php" class="button">Back to Dashboard</a></li>
                    </ul>
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
