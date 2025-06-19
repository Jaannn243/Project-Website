<?php
include "connection.php";

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];

    // Query untuk memperbarui data pengguna
    $sql = "UPDATE author SET nickname = '$nickname', email = '$email' WHERE id = '$id'";

    if ($koneksi->query($sql) === TRUE) {
        echo "Pengguna berhasil diperbarui!";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>
