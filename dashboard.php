<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi.php';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - FIXTLAPBOT ID</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />
    <link rel="stylesheet" href="style.css" />
    
    <style>
      .dash-banner {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9; 
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      }

      @media (min-width: 768px) {
        .dash-banner {
           max-height: 450px;
        }
      }

      .dash-banner video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
      }
      .dash-banner .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2));
        z-index: 1;
        pointer-events: none; 
      }
      .dash-banner .banner-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 40px;
        color: white;
        pointer-events: none; 
      }
    </style>
  </head>
  <body class="bg-light dashboard-bg">
    <nav class="navbar navbar-expand-lg custom-navbar mb-4">
      <div class="container-fluid px-4">
        <a class="navbar-brand-dash" href="#">FIXTLAPBOT ID</a>
        <div class="d-flex">
          <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
      </div>
    </nav>

    <div class="container-fluid px-4">
      
      <div class="dash-banner">
        <video autoplay loop playsinline controls>
          <source src="video/bg-login.mp4" type="video/mp4">
          Video tidak didukung oleh browser Anda.
        </video>
        <div class="overlay"></div>
        <div class="banner-content">
          <h3 class="fw-bold mb-1" style="letter-spacing: 1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">FIXTLAPBOT ID</h3>
          <p class="mb-0 text-light" style="font-size: 15px; text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">Sistem Cerdas Servis Laptop Berbasis Robot AI.</p>
        </div>
      </div>

      <div class="dash-card">
        <div class="dash-card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">Daftar Service Laptop</h5>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#serviceModal">
            + Tambah Service Baru
          </button>
        </div>
        <div class="card-body p-4">
          <div class="table-responsive">
            <table id="serviceTable" class="table table-striped" style="width: 100%">
              <thead>
                <tr>
                  <th>No Resi</th>
                  <th>Nama Pelanggan</th>
                  <th>Merk/Tipe</th>
                  <th>Keluhan</th>
                  <th>Foto Fisik</th>
                  <th>Tanda Tangan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT * FROM data_service ORDER BY tanggal_masuk DESC";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $badge_class = ($row['status'] == 'Proses') ? 'badge-proses' : 'badge-selesai';
                        
                        echo "<tr>";
                        echo "  <td>" . htmlspecialchars($row['no_resi']) . "</td>";
                        echo "  <td>" . htmlspecialchars($row['nama_pelanggan']) . "</td>";
                        echo "  <td>" . htmlspecialchars($row['merk_laptop']) . "</td>";
                        echo "  <td>" . htmlspecialchars($row['keluhan']) . "</td>";

                        if (!empty($row['foto_bukti'])) {
                            $fotos = explode(',', $row['foto_bukti']);
                            echo "  <td>";
                            foreach($fotos as $foto) {
                                $nama_foto = htmlspecialchars(trim($foto));
                                echo "<img src='uploads/" . $nama_foto . "' alt='Foto' style='max-width: 50px; margin-right: 4px; border-radius: 4px; border: 1px solid #ccc;'>";
                            }
                            echo "  </td>";
                        } else {
                            echo "  <td><span class='badge bg-light text-secondary'>Tidak ada foto</span></td>";
                        }

                        if (!empty($row['tanda_tangan'])) {
                            echo "  <td><img src='" . htmlspecialchars($row['tanda_tangan']) . "' alt='TTD' style='max-width: 90px; border: 1px solid #e2e8f0; background: #fff; border-radius: 4px; padding: 2px;'></td>";
                        } else {
                            echo "  <td>-</td>";
                        }

                        echo "  <td><span class='" . $badge_class . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "  <td>
                                  <button class='btn btn-sm btn-info text-white mb-1' onclick='editData(" . $row['id'] . ")'>Edit</button>
                                  <button class='btn btn-sm btn-danger mb-1' onclick='hapusData(" . $row['id'] . ")'>Hapus</button>
                                </td>";
                        echo "</tr>";
                    }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold" id="serviceModalLabel">Form Data Service Laptop</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="serviceForm">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Pelanggan</label>
                  <input type="text" class="form-control" id="nama_pelanggan" placeholder="Nama lengkap" />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Merk/Tipe Laptop</label>
                  <input type="text" class="form-control" id="merk_laptop" placeholder="Contoh: Acer Nitro 5" />
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Keluhan / Kerusakan</label>
                <textarea class="form-control" rows="3" id="keluhan" placeholder="Jelaskan kerusakan detail..."></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Upload Foto Bukti/Fisik Laptop</label>
                <input class="form-control" type="file" id="foto_bukti" accept="image/*" multiple />
                <small class="text-muted">Tekan Ctrl (atau tahan) saat memilih file untuk mengunggah lebih dari satu foto.</small>
              </div>
              <div class="mb-3">
                <label class="form-label">Tanda Tangan Pelanggan</label>
                <div class="signature-wrapper">
                  <canvas id="signature-pad"></canvas>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" id="clear-signature">Hapus Tanda Tangan</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-indigo" onclick="saveData()">Simpan Data</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title fw-bold">Edit Data Service</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editForm">
              <input type="hidden" id="edit_id" />
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Pelanggan</label>
                  <input type="text" class="form-control" id="edit_nama" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Merk/Tipe Laptop</label>
                  <input type="text" class="form-control" id="edit_merk" required />
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Keluhan / Kerusakan</label>
                <textarea class="form-control" rows="3" id="edit_keluhan" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Status Pengerjaan</label>
                <select class="form-select" id="edit_status">
                  <option value="Proses">Proses (Sedang Dikerjakan)</option>
                  <option value="Selesai">Selesai (Siap Diambil)</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-info text-white" onclick="updateData()">Simpan Perubahan</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="script.js?v=2"></script>
  </body>
</html>