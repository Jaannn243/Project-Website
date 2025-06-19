<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/main.css"> <!-- Mengarah ke file CSS yang sudah disesuaikan -->
</head>
<body>
    <div class="form-container">
        <form action="aksi_login.php?op=in" method="POST">
            <h2>Login</h2>
            <div>
                <label for="nickname">Username</label>
                <input type="text" id="nickname" name="nickname" required placeholder="Masukkan username">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>
            <div>
                <input type="submit" name="login" value="Login">
            </div>
            <div class="form-links">
                <a href="form_register.php">Belum punya akun? Daftar di sini</a>
            </div>
        </form>
    </div>
</body>
</html>
