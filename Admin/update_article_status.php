<?php
include "connection.php";

// Ambil data dari request POST
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update status artikel
    $sql = "UPDATE article SET status = '$status' WHERE id = '$id'";

    if ($koneksi->query($sql) === TRUE) {
        echo "Status artikel berhasil diperbarui menjadi $status.";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>
