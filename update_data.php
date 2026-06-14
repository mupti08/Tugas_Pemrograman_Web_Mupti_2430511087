<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['edit_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['edit_nama']);
    $merk = mysqli_real_escape_string($conn, $_POST['edit_merk']);
    $keluhan = mysqli_real_escape_string($conn, $_POST['edit_keluhan']);
    $status = mysqli_real_escape_string($conn, $_POST['edit_status']);

    $query = "UPDATE data_service SET 
                nama_pelanggan = '$nama', 
                merk_laptop = '$merk', 
                keluhan = '$keluhan', 
                status = '$status' 
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Data Service berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . mysqli_error($conn)]);
    }
}
?>