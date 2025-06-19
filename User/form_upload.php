<?php
session_start();
include 'connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL); // Tampilkan semua error

if (!isset($_SESSION['nickname'])) {
    header('Location: login.php');
    exit();
}

$nickname = $_SESSION['nickname']; // Ambil nickname dari session
$sql_user = "SELECT nickname, email FROM author WHERE nickname = '$nickname'";
$result_user = $koneksi->query($sql_user);

// Ambil data user
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $nickname = $user['nickname'];
    $email = $user['email'];
}


// Ambil ID author berdasarkan nickname
$author_sql = "SELECT id FROM author WHERE nickname = '$nickname'";
$author_result = $koneksi->query($author_sql);
if ($author_result->num_rows > 0) {
    $author = $author_result->fetch_assoc();
    $author_id = $author['id'];  // Ambil ID author
} else {
    die("Author not found.");
}

// Ambil kategori dari database untuk ditampilkan di form
$categories = [];
$category_sql = "SELECT * FROM category";
$category_result = $koneksi->query($category_sql);
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row; // Menyimpan kategori dalam array
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $title = $koneksi->real_escape_string($_POST['title']);
    $content = $koneksi->real_escape_string($_POST['content']);
    $category_id = $_POST['category_id']; // Category ID dari dropdown
    $date = date('Y-m-d H:i:s'); // Ambil waktu saat ini

    // Proses upload gambar
    $upload_dir = '../assets/image/';
    $picture = $_FILES['picture']['name'];
    $tmp_name = $_FILES['picture']['tmp_name'];
    $file_path = $upload_dir . basename($picture);

    // Cek apakah file upload tidak error
    if ($_FILES['picture']['error'] == UPLOAD_ERR_OK) {
        // Cek apakah direktori upload ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);  // Membuat folder jika tidak ada
        }

        // Sanitasi nama file
        $picture = preg_replace("/[^a-zA-Z0-9\.]/", "_", $picture);  // Mengganti karakter yang tidak diizinkan

        // Pindahkan file gambar ke folder uploads
        if (move_uploaded_file($tmp_name, $file_path)) {
            // Masukkan artikel ke tabel article
            $sql = "INSERT INTO article (title, content, date, picture, status) VALUES ('$title', '$content', '$date', '$picture', 'Menunggu Persetujuan')";
            if ($koneksi->query($sql) === TRUE) {
                $article_id = $koneksi->insert_id; // Ambil ID artikel yang baru saja dimasukkan

                // Masukkan data ke tabel article_author untuk menghubungkan artikel dengan author
                $sql_article_author = "INSERT INTO article_author (article_id, author_id) VALUES ('$article_id', '$author_id')";
                if ($koneksi->query($sql_article_author) === TRUE) {
                    // Masukkan kategori artikel ke tabel article_category
                    $sql_category = "INSERT INTO article_category (article_id, category_id) VALUES ('$article_id', '$category_id')";
                    if ($koneksi->query($sql_category) === TRUE) {
                        echo "Article uploaded successfully and waiting for approval!";
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        echo "Error adding category: " . $koneksi->error;
                    }
                } else {
                    echo "Error adding to article_author: " . $koneksi->error;
                }
            } else {
                echo "Error uploading article: " . $koneksi->error;
            }
        } else {
            echo "Failed to move the uploaded file.";
        }
    } else {
        echo "Error uploading file: " . $_FILES['picture']['error'];
    }
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>KataKita</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
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

                <!-- Form Upload Artikel -->
                <section>
                    <header class="major">
                        <h2>Upload Article</h2>
                    </header>

                    <form method="POST" action="form_upload.php" enctype="multipart/form-data">
                        <div>
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required />
                        </div>
                        <div>
                            <label for="content">Content</label>
                            <!-- CKEditor implementation for content field -->
                            <textarea id="content" name="content" rows="6"></textarea>
                            <script>
                                ClassicEditor
                                    .create(document.querySelector('#content'))
                                    .catch(error => {
                                        console.error(error);
                                    });
                            </script>
                        </div>
                        <div>
                            <label for="category">Category</label>
                            <select name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="picture">Picture</label>
                            <input type="file" id="picture" name="picture" required />
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Upload Article" />
                        </div>
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
                        <li><a href="index.php">Homepage</a></li>
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
                    <p>Selamat datang di website kami <strong>KataKita</strong>, jangan lupa ketika anda membutuhkan artikel untuk membantu pekerjaan anda ataupun sedang dalam posisi Gabut, anda bisa meengunjungi website kami untuk membaca tentang sesuatu yang mungkin anda tertarik!.</p>
                    <ul class="contact">
                        <li class="icon solid fa-user"><a href="#"><?= htmlspecialchars($nickname); ?></a></li>
                        <li class="icon solid fa-envelope"><a href="mailto:<?= htmlspecialchars($email); ?>"><?= htmlspecialchars($email); ?></a></li>
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