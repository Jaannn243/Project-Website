<?php
include "connection.php";

// Cek apakah ada parameter id di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus kategori berdasarkan ID
    $sql = "DELETE FROM category WHERE id = '$id'";

    // Eksekusi query
    if ($koneksi->query($sql) === TRUE) {
        // Jika berhasil, redirect kembali ke halaman kategori dengan status success
        header("Location: kategori.php?status=deleted");
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
} else {
    // Jika tidak ada ID yang dikirimkan
    echo "ID kategori tidak ditemukan.";
}
?>
