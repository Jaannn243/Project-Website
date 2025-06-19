<?php
include "connection.php";

// Cek jika ada parameter ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus pengguna berdasarkan ID
    $sql = "DELETE FROM author WHERE id = '$id'";

    if ($koneksi->query($sql) === TRUE) {
        echo "Pengguna berhasil dihapus!";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>
