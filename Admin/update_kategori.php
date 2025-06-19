<?php
include "connection.php";

// Cek apakah ada data yang dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id']; // ID kategori yang ingin diperbarui
    $name = $_POST['name']; // Nama kategori baru
    $description = $_POST['description']; // Deskripsi kategori baru

    // Query untuk memperbarui data kategori berdasarkan ID
    $sql = "UPDATE category SET name = '$name', description = '$description' WHERE id = '$id'";

    // Eksekusi query
    if ($koneksi->query($sql) === TRUE) {
        // Jika berhasil, redirect kembali ke halaman kategori
        header("Location: kategori.php?status=success");
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
} else {
    echo "Data tidak ditemukan!";
}
?>
