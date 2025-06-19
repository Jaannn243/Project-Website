<?php
session_start();
include 'connection.php';
$nick = $_POST['nickname'];
$psw = $_POST['password'];
$op = $_GET['op'];

if($op=="in"){
  $sql = "SELECT * from author where nickname='$nick' AND password='$psw'";
  $query = $koneksi->query($sql);
  if(mysqli_num_rows($query)==1){
    $data = $query->fetch_array();
    $_SESSION['nickname'] = $data['nickname'];
    $_SESSION['level'] = $data['level'];
    if($data['level'] == 'Admin'){
      header("location:index.php");
    }else if($data['level'] == 'Author'){
      header("location:index.php");
    }
  }else{
    die("password salah <a href=\"javascript:history.back()\">kembali</a>");
  }
}else if($op=="out"){
  unset($_SESSION['nickname']);
  unset($_SESSION['level']);
  header("location:login.php");
}
?>