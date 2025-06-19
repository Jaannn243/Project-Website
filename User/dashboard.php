<?php
session_start();
include 'connection.php';

// Cek apakah pengguna sudah login
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

// Query untuk mengambil artikel berdasarkan author_id melalui tabel article_author
$sql = "SELECT a.*, c.name AS category_name 
        FROM article a
        LEFT JOIN article_category ac ON a.id = ac.article_id
        LEFT JOIN category c ON ac.category_id = c.id
        LEFT JOIN article_author aa ON a.id = aa.article_id
        WHERE aa.author_id = (SELECT id FROM author WHERE nickname = '$nickname')";
$result = $koneksi->query($sql);

// Pastikan $articles selalu didefinisikan, bahkan jika tidak ada artikel ditemukan
$articles = [];  // Inisialisasi array kosong

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;  // Menambahkan artikel ke dalam array
    }
} else {
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>KataKita</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <style>
        /* Styling untuk modal */
        .modal {
            display: none;
            /* Modal disembunyikan secara default */
            position: fixed;
            /* Posisi tetap di layar */
            z-index: 1;
            /* Agar modal di atas konten lainnya */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* Latar belakang transparan */
        }

        /* Modal Konten */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        /* Tombol Modal */
        .modal-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>

<body class="is-preload">
    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main -->
        <div id="main">
            <div class="inner">

                <!-- Header -->
                <header id="header">
                    <a href="../index.php" class="logo"><strong>KataKita</strong> || By Educational Community</a>
                    <ul class="icons">
                        <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
                        <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
                        <li><a href="#" class="icon brands fa-snapchat-ghost"><span class="label">Snapchat</span></a></li>
                        <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
                        <li><a href="#" class="icon brands fa-medium-m"><span class="label">Medium</span></a></li>
                    </ul>
                </header>
                <!-- Table for displaying articles -->

                <section>
                    <header class="major">
                        <h2>Your Articles</h2>
                    </header>

                    <!-- Table to display articles -->
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Picture Filename</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($articles)) : ?>
                                <?php foreach ($articles as $article) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($article['date']); ?></td>
                                        <td><?= htmlspecialchars($article['title']); ?></td>
                                        <td><?= htmlspecialchars(substr($article['content'], 0, 100)); ?>...</td>
                                        <td><?= htmlspecialchars($article['picture']); ?></td>
                                        <td>
                                            <a href="edit_artikel.php?edit=<?= $article['id']; ?>" class="button">Edit</a>
                                            <button class="button delete-btn" data-id="<?= $article['id']; ?>" data-title="<?= $article['title']; ?>">Delete</button>
                                        </td>
                                        <!-- Modal Konfirmasi -->
                                        <div id="deleteModal" class="modal">
                                            <div class="modal-content">
                                                <h3>Apakah kamu yakin ingin menghapus artikel ini?</h3>
                                                <div class="modal-footer">
                                                    <button id="confirmDelete" class="button primary">Ya</button>
                                                    <button id="cancelDelete" class="button">Tidak</button>
                                                </div>
                                            </div>
                                        </div>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">No articles found.</td>
                                </tr>
                            <?php endif; ?>
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
                <!-- Data Diri User (Contact Section) -->
                <section>
                    <header class="major">
                        <h2>Get in touch</h2>
                    </header>
                    <p>Selamat datang di website kami <strong>KataKita</strong>, jangan lupa ketika anda membutuhkan artikel untuk membantu pekerjaan anda ataupun sedang dalam posisi Gabut, anda bisa meengunjungi website kami untuk membaca tentang sesuatu yang mungkin anda tertarik!.</p>
                    <ul class="contact">
                        <!-- Menampilkan nickname dan email dari database -->
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
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen modal dan tombol
            var modal = document.getElementById("deleteModal");
            var cancelBtn = document.getElementById("cancelDelete");
            var confirmBtn = document.getElementById("confirmDelete");
            var deleteBtns = document.querySelectorAll(".delete-btn");

            var articleId;

            // Menampilkan modal saat tombol delete ditekan
            deleteBtns.forEach(function(button) {
                button.addEventListener("click", function() {
                    articleId = this.getAttribute("data-id");
                    var articleTitle = this.getAttribute("data-title");
                    document.querySelector(".modal-content h3").textContent = "Apakah kamu yakin ingin menghapus artikel '" + articleTitle + "'?";
                    modal.style.display = "block";
                });
            });

            // Menutup modal
            cancelBtn.onclick = function() {
                modal.style.display = "none";
            };

            // Menghapus artikel jika tombol "Ya" ditekan
            confirmBtn.onclick = function() {
                window.location.href = "hapus_artikel.php?id=" + articleId; // Redirect ke hapus_artikel.php untuk menghapus artikel
            };

            // Menutup modal jika klik di luar modal
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        });
    </script>

</body>

</html>