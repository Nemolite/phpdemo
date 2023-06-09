<?php session_start();?>
<!-- Подключение необходимых файлов-->
<?php include "connect.php"; ?>
<?php include "function.php"; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
<header class="header">
    <div class="header-main">
        <div class="headre-logo">
            <a href="/">
                <img src="images/logo.png" alt="Логотип">
            </a>
        </div>
        <div class="header-menu">
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <?php
                    if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
                    ?>
                    <li><a href="cart.php">Корзина</a></li>
                    <li><a href="account.php">Аккаунт</a></li>
                    <?php } ?>
                    <li><a href="">О нас</a></li>
                    <li><a href="">Наши контакты</a></li>

                </ul>
            </nav>
        </div>
        <div class="header-login">
            <?php
            if (isset($_SESSION['id'])&&!empty($_SESSION['id'])) {
            ?>
                <p>Привет, <?php echo getNameUser($pdo);?>
                <a href="http://<?= $_SERVER["SERVER_NAME"]?>/?exit=true">Выход</a></p>
                <?php
                 if (isset($_GET['exit'])){
                     unset($_SESSION['id']);
                     header('index.php');
                 }
             ?>

            <?php } else { ?>
            <p><a href="register.php">Регистрация</a> / <a href="login.php">Вход</a></p>
            <?php } ?>
        </div>
    </div>
</header>

