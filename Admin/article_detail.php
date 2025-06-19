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

// Ambil ID artikel dari URL
$article_id = $_GET['id'];

// Query untuk mengambil artikel berdasarkan ID
$sql_article = "SELECT * FROM article WHERE id = '$article_id' AND status = 'Disetujui'"; // Pastikan hanya artikel yang disetujui yang ditampilkan
$result_article = $koneksi->query($sql_article);

// Jika artikel ditemukan
if ($result_article->num_rows > 0) {
    $article = $result_article->fetch_assoc();
    $title = $article['title'];
    $content = $article['content'];
    $date = $article['date'];
    $picture = $article['picture'];
} else {
    echo "Artikel tidak ditemukan atau belum disetujui.";
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Detail Artikel - KataKita</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        /* Styling untuk artikel detail */
        .article-detail {
            margin: 20px;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        /* Styling untuk gambar artikel */
        .article-detail .image {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .article-detail .image img {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .article-detail h2 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }
        .article-detail .date {
            text-align: center;
            color: #888;
            font-size: 1em;
            margin-bottom: 20px;
        }
        .article-detail .content {
            font-size: 1.2em;
            line-height: 1.6;
            color: #333;
        }
    </style>
</head>
<body class="is-preload">
    <div id="wrapper">
        <div id="main">
            <div class="inner">
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

                <section>
                    <div class="article-detail">
                        <h2><?php echo htmlspecialchars($title); ?></h2>
                        <div class="image">
                            <img src="../assets/image/<?php echo $picture; ?>" alt="Image Artikel">
                        </div>
                        <p class="date"><?php echo $date; ?></p>
                        <div class="content">
                            <?php echo $article['content']; ?>
                        </div>
                    </div>
                    <li><a href="artikel.php" class ="button">Back To Artikel</a></li>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
