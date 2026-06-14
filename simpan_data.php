<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $merk = mysqli_real_escape_string($conn, $_POST['merk_laptop']);
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $tanda_tangan = $_POST['tanda_tangan'];
    
    $no_resi = 'SV-' . time();
    $uploaded_files = []; 

    if (isset($_FILES['foto_bukti'])) {

        if (is_array($_FILES['foto_bukti']['name'])) {
            $total_files = count($_FILES['foto_bukti']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['foto_bukti']['error'][$i] == 0 && !empty($_FILES['foto_bukti']['name'][$i])) {
                    $nama_file = time() . "_" . $i . "_" . basename($_FILES["foto_bukti"]["name"][$i]);
                    $target_dir = "uploads/" . $nama_file;
                    if (move_uploaded_file($_FILES["foto_bukti"]["tmp_name"][$i], $target_dir)) {
                        $uploaded_files[] = $nama_file; 
                    }
                }
            }
        } 

        else {
            if ($_FILES['foto_bukti']['error'] == 0 && !empty($_FILES['foto_bukti']['name'])) {
                $nama_file = time() . "_0_" . basename($_FILES["foto_bukti"]["name"]);
                $target_dir = "uploads/" . $nama_file;
                if (move_uploaded_file($_FILES["foto_bukti"]["tmp_name"], $target_dir)) {
                    $uploaded_files[] = $nama_file;
                }
            }
        }
    }

    $string_foto = implode(',', $uploaded_files);

    $query = "INSERT INTO data_service (no_resi, nama_pelanggan, merk_laptop, keluhan, foto_bukti, tanda_tangan, status) 
              VALUES ('$no_resi', '$nama', '$merk', '$keluhan', '$string_foto', '$tanda_tangan', 'Proses')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Mantap! Data Service dan file berhasil disimpan.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . mysqli_error($conn)]);
    }
}
?>