<?php
session_start();
include 'connection.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nickname'])) {
    header('Location: login.php');
    exit();
}

// Cek apakah ada ID artikel yang akan dihapus
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Query untuk menghapus artikel dari tabel artikel
    $sql = "DELETE FROM article WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $article_id);

    if ($stmt->execute()) {
        // Penghapusan berhasil, arahkan kembali ke halaman artikel
        header("Location: artikel.php");
        exit();
    } else {
        echo "Error: " . $koneksi->error;
    }
} else {
    // Jika tidak ada ID yang diterima, arahkan ke halaman artikel
    header("Location: artikel.php");
    exit();
}
?>
