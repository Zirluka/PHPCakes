<?php
// Подключаем core.php
include './module/core.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="./css/style.css">
</head>
<body>
	<div class="main_container">
	<header>
		<div class="logo">
			<a href="index.php"><h1>Домашние торты</h1></a>
		</div>
		<nav>
			<a href="index.php" class="<?php if($current_page == "index"): ?> active <?php endif; ?> header_link">Главная</a>
			<a href="about.php" class="<?php if($current_page == "about"): ?> active <?php endif; ?> header_link">О нас</a>
			<a href="catalog.php" class="<?php if($current_page == "catalog"): ?> active <?php endif; ?> header_link">Каталог</a>
			<a href="news.php" class="<?php if($current_page == "news"): ?> active <?php endif; ?> header_link">Новости</a>
			<a href="contacts.php" class="<?php if($current_page == "contacts"): ?> active <?php endif; ?> header_link">Контакты</a>

			<a href="register.php" class="<?php if($current_page == "register"): ?> active <?php endif; ?> header_link">Регистрация</a>
			<a href="login.php" class="<?php if($current_page == "login"): ?> active <?php endif; ?> header_link">Авторизация</a>

		</nav>
	</header>
	