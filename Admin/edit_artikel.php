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
    $sql = "SELECT * FROM article WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();

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
    $picture = $_POST['picture']; // Menyimpan nama file gambar (bisa juga diupload di server)

    // Jika ada gambar yang diupload, proses upload gambar
    if ($_FILES['file_picture']['error'] == 0) {
        $upload_dir = 'uploads/'; // Pastikan folder ini ada
        $uploaded_file = $upload_dir . basename($_FILES['file_picture']['name']);
        move_uploaded_file($_FILES['file_picture']['tmp_name'], $uploaded_file);
        $picture = basename($_FILES['file_picture']['name']);
    }

    // Update artikel di database
    $update_sql = "UPDATE article SET title = ?, content = ?, picture = ? WHERE id = ?";
    $stmt_update = $koneksi->prepare($update_sql);
    $stmt_update->bind_param('sssi', $title, $content, $picture, $article_id);

    if ($stmt_update->execute()) {
        header("Location: index.php");
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
                    <a href="index.html" class="logo"><strong>Editorial</strong> by HTML5 UP</a>
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

                    <form method="POST" action="edit_artikel.php?edit=<?= $article_id; ?>" enctype="multipart/form-data">
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
                                <td>Upload New Image</td>
                                <td><input type="file" name="file_picture" /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" value="Update Article" /></td>
                            </tr>
                        </table>
                    </form>

                    <!-- Link Kembali ke Dashboard -->
                    <ul class="actions">
                        <li><a href="index.php" class="button">Back to Dashboard</a></li>
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
