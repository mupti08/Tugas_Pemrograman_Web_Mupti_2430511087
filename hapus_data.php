<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $queryCariFoto = "SELECT foto_bukti FROM data_service WHERE id = '$id'";
    $hasilFoto = mysqli_query($conn, $queryCariFoto);
    $dataFoto = mysqli_fetch_assoc($hasilFoto);

    if (!empty($dataFoto['foto_bukti'])) {
        $fotos = explode(',', $dataFoto['foto_bukti']); 

        foreach ($fotos as $foto) {
            $pathFile = "uploads/" . trim($foto);
            if (file_exists($pathFile)) {
                unlink($pathFile); 
            }
        }
    }

    $queryHapus = "DELETE FROM data_service WHERE id = '$id'";
    
    if (mysqli_query($conn, $queryHapus)) {
        echo json_encode(['status' => 'success', 'message' => 'Data dan semua foto terkait berhasil dihapus bersih!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data dari database.']);
    }
}
?>