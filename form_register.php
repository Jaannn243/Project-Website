<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/main.css"> <!-- Mengarah ke file CSS yang sudah disesuaikan -->
</head>
<body>
    <div class="form-container">
        <form action="aksi_register.php" method="POST">
            <h2>Register</h2>
            <div>
                <label for="nickname">Username</label>
                <input type="text" id="nickname" name="nickname" required placeholder="Masukkan username">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>
            <div>
                <label for="level">Level</label>
                <select id="level" name="level">
                    <option value="Author">Author</option>
                </select>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email">
            </div>
            <div>
                <input type="submit" name="register" value="Register">
            </div>
            <div class="form-links">
                <a href="login.php">Sudah punya akun? Login di sini</a>
            </div>
        </form>
    </div>
</body>
</html>
