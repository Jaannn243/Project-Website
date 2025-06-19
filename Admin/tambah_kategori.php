<?php
include "connection.php";

// Cek jika request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Menyimpan data kategori ke database
    $sql = "INSERT INTO category (name, description) VALUES ('$name', '$description')";
    
    if ($koneksi->query($sql) === TRUE) {
        echo "Kategori berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>
