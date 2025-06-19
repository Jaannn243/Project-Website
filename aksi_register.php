<?php
include "connection.php";
$nick = $_POST['nickname'];
$psw = $_POST['password'];
$level = $_POST['level'];
$email = $_POST['email'];
$sql="INSERT INTO author (nickname, password, level, email) VALUES ('".$nick."','".$psw."','".$level."','".$email."')";

$query=$koneksi->query($sql);

if ($query === true) {
header('location: login.php');
} else {
echo "eroooooorrrrr";
}

?>