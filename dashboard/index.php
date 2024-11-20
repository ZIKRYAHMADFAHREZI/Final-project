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
<div class="text-center">
    <form action="" method="post">
        <input type="text" name="keyword" size="30" autofocus 
        placeholder="Masukkan keyword pencarian" autocomplete="off">
        <button type="submit" name="cari">cari!</button>
    </form>
</div>
</body>
</html>