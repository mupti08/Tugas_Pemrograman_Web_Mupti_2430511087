<?php
session_start();
require 'koneksi.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Username atau Password salah!";
        }
    } else {
        $error_message = "Semua kolom wajib diisi!";
    }
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - FIXTLAPBOT ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body class="login-bg">
    <div class="login-card">
      <div class="text-center mb-4">
        <h3 class="fw-bold app-title mb-1">FIXTLAPBOT ID</h3>
        <p class="text-muted small">Laptop Management Service Platform</p>
      </div>

      <div class="alert alert-info text-center py-2 px-3 mb-4" style="font-size: 13px; border-radius: 8px;">
        Gunakan akun demo berikut untuk masuk:<br>
        <strong>Username:</strong> <code>admin</code> &nbsp;|&nbsp; <strong>Password:</strong> <code>admin123</code>
      </div>

      <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger text-center small py-2" role="alert">
          <?php echo $error_message; ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required />
        </div>
        <div class="d-grid gap-2 mt-4">
          <button type="submit" class="btn btn-indigo w-100">Masuk ke Dashboard</button>
        </div>
      </form>

      <div class="divider">ATAU MASUK DENGAN</div>

      <div class="d-flex justify-content-center">
        <div id="g_id_onload" data-client_id="YOUR_GOOGLE_CLIENT_ID" data-context="signin" data-ux_mode="popup" data-callback="handleGoogleLogin" data-auto_prompt="false"></div>
        <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left"></div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="script.js"></script>
  </body>
</html>