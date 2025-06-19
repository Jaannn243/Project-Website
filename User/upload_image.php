<?php
// Pastikan file gambar diunggah dengan benar
if ($_FILES['upload']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = '../assets/image/';
    $upload_file = $upload_dir . basename($_FILES['upload']['name']);
    
    // Pindahkan file gambar dari tmp ke folder yang ditentukan
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $upload_file)) {
        // Berikan respon JSON dengan URL gambar yang telah diunggah
        $response = array(
            'uploaded'=> true,
            'url' => $upload_file
        );
        echo json_encode($response);
    } else {
        echo json_encode(array('uploaded' => false, 'error' => 'Failed to upload image.'));
    }
} else {
    echo json_encode(array('uploaded' => false, 'error' => 'No file uploaded.'));
}
?>