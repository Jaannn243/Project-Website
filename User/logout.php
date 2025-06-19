<?php
session_start(); // Mulai sesi
// Hancurkan sesi dan hapus data sesi
if (isset($_GET['logout']) && $_GET['logout'] == 'yes') {
    session_unset();  // Hapus semua data sesi
    session_destroy(); // Hancurkan sesi
    header("Location: ../login.php");  // Arahkan pengguna ke halaman login
    exit();
} 
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Logout - KataKita</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        /* Styling untuk modal */
        .modal {
            display: block; /* Modal ditampilkan */
            position: fixed;
            z-index: 1; /* Agar modal berada di atas */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Latar belakang semi-transparan */
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            margin: 10% auto;
            text-align: center;
        }

        .modal button {
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
        }

        .modal button.confirm {
            background-color: rgb(231, 206, 94);
            color: white;
        }

        .modal button.cancel {
            background-color: #ccc;
        }

        /* Button Styling for Logout */
        .logout-button {
            background-color: rgb(255, 72, 0);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: rgb(14, 13, 13);
        }
    </style>
</head>

<body class="is-preload">
    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main -->
        <div id="main">
            <div class="inner">

                <!-- Header -->
                <header id="header">
                    <a href="../index.php" class="logo"><strong>KataKita</strong> || By Educational Community</a>
                </header>

                <!-- Modal Logout -->
                <div class="modal">
                    <div class="modal-content">
                        <h2>Apakah Anda Yakin Ingin Logout Sekarang?</h2>
                        <!-- Tombol Tidak akan kembali ke index.php tanpa logout -->
                        <button class="cancel" onclick="window.location.href='../index.php';">Tidak</button>
                        <!-- Tombol Ya akan mengarahkan ke logout.php dengan parameter logout=yes -->
                        <a href="?logout=yes"><button class="confirm">Ya</button></a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>
