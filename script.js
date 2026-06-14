function handleGoogleLogin(response) {
  alert("Login Google Berhasil! Mengalihkan ke Dashboard...");
  window.location.href = "dashboard.php";
}

var signaturePad;

$(document).ready(function () {
  if ($("#serviceTable").length) {
    $("#serviceTable").DataTable({
      dom: "Bfrtip",
      buttons: ["excel", "pdf", "print"],
      language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
    });
  }

  var canvas = document.getElementById("signature-pad");
  if (canvas) {
    function resizeCanvas() {
      var ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext("2d").scale(ratio, ratio);
    }
    window.onresize = resizeCanvas;
    resizeCanvas();

    signaturePad = new SignaturePad(canvas, {
      backgroundColor: "rgba(255, 255, 255, 0)",
      penColor: "rgb(0, 0, 0)",
    });
    document
      .getElementById("clear-signature")
      .addEventListener("click", () => signaturePad.clear());
    $("#serviceModal").on("shown.bs.modal", () => {
      resizeCanvas();
      signaturePad.clear();
    });
  }
});

function saveData() {
  var nama = $("#nama_pelanggan").val();
  var merk = $("#merk_laptop").val();
  var keluhan = $("#keluhan").val();

  if (nama === "" || merk === "" || keluhan === "") {
    alert("Mohon lengkapi data!");
    return;
  }
  if (signaturePad.isEmpty()) {
    alert("Tanda tangan wajib diisi!");
    return;
  }

  var formData = new FormData();
  formData.append("nama_pelanggan", nama);
  formData.append("merk_laptop", merk);
  formData.append("keluhan", keluhan);
  formData.append("tanda_tangan", signaturePad.toDataURL("image/png"));

  var fotoFiles = $("#foto_bukti")[0].files;
  for (var i = 0; i < fotoFiles.length; i++) {
    formData.append("foto_bukti[]", fotoFiles[i]);
  }

  $.ajax({
    url: "simpan_data.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      try {
        var res = JSON.parse(response);
        if (res.status === "success") {
          alert(res.message);
          location.reload();
        } else {
          alert("Error: " + res.message);
        }
      } catch (e) {
        alert("Gagal memproses data dari server.");
      }
    },
    error: function () {
      alert("Terjadi kesalahan sistem saat mengirim data.");
    },
  });
}

function hapusData(id_data) {
  if (
    confirm("Yakin ingin menghapus data ini? Semua foto akan ikut terhapus.")
  ) {
    $.ajax({
      url: "hapus_data.php",
      type: "POST",
      data: { id: id_data },
      success: function (response) {
        try {
          var res = JSON.parse(response);
          if (res.status === "success") {
            alert(res.message);
            location.reload();
          } else {
            alert("Error: " + res.message);
          }
        } catch (e) {
          alert("Gagal memproses penghapusan.");
        }
      },
    });
  }
}

function editData(id_data) {
  $.ajax({
    url: "ambil_data.php",
    type: "POST",
    data: { id: id_data },
    success: function (response) {
      try {
        var data = JSON.parse(response);
        $("#edit_id").val(data.id);
        $("#edit_nama").val(data.nama_pelanggan);
        $("#edit_merk").val(data.merk_laptop);
        $("#edit_keluhan").val(data.keluhan);
        $("#edit_status").val(data.status);
        $("#editModal").modal("show");
      } catch (e) {
        alert(
          "Gagal mengambil data. Pastikan file ambil_data.php sudah dibuat.",
        );
      }
    },
  });
}

function updateData() {
  $.ajax({
    url: "update_data.php",
    type: "POST",
    data: {
      edit_id: $("#edit_id").val(),
      edit_nama: $("#edit_nama").val(),
      edit_merk: $("#edit_merk").val(),
      edit_keluhan: $("#edit_keluhan").val(),
      edit_status: $("#edit_status").val(),
    },
    success: function (response) {
      try {
        var res = JSON.parse(response);
        if (res.status === "success") {
          alert(res.message);
          location.reload();
        } else {
          alert("Error: " + res.message);
        }
      } catch (e) {
        alert("Gagal memproses pembaharuan.");
      }
    },
  });
}
