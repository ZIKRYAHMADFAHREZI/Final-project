<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin</title>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<style>
    header {
        background-color: aqua;
        padding: 10px;
    }
</style>
</head>
<body>
<header>
<div class="wrapper">
            <header class="main-header">
                <a href="https://member.ionbroadband.id/dashboard" class="logo">
                    <span class="logo-mini">
                        <b>C</b>
                        P
                    </span>
                    <span class="logo-lg">
                        <b>Customer Portal</b>
                    </span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
</header>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../img/kt1.png" class="img-circle">
            </div>
            <div class="pull-left info">
                <a href="#">
                    <i class="fa fa-circle text-success"></i>
                    logged in
                </a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Navigasi Admin</li>
            <li class="active">
                <a href="index.php>
                    <i class="fa fa-home"></i>
                    <span>Beranda</span>
                </a>
            <li>
                <a href="updatePw.php">
                    <i class="fa fa-lock"></i>
                    <span>Ganti Email & Password</span>
                </a>
    </section>
</aside>
    <div>
    <form action="" method="post" enctype="multipart/form-data">
        <ul>
            <li>
                <label for="judul_buku">Email baru: </label>
                <input type="text" name="judul_buku" required value="">
            </li>
            <li>
                <label for="kategori_nama">Password lama: </label>
                <input type="text" name="kategori_nama" value="">
            </li>
            <li>
                <label for="penerbit_nama">Password baru: </label>
                <input type="text" name="penerbit_nama" required value="">
            </li>
            <li>
                <label for="pengarang_nama">Konfirmasi password: </label>
                <input type="text" name="pengarang_nama" required value="">
        </ul>
        <button type="submit" name="submit">cancel</button>
        <button type="submit" name="submit">Ubah data!</button>
    </div>
</body>
</html>