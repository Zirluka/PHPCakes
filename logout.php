<?php
// Подключаем header.php
$title = "Выход";
$current_page = "logout";
require_once './template/header.php';

// Проверяем, авторизирован ли пользователь
$token = $_SESSION['token'] ?? null;
if (!$token) {
	// Если нет, то перебрасывем его на страницу входа
	header("Location: login.php");
	die();
}

// Если токен есть, то делаем запрос в БД на обновление токена на null
$query = mysqli_helper::get_update_query("users", "`token`=null", 'token','=',"'$token'");
mysqli_query($link, $query);
// Удаляем токен из сессии
unset($_SESSION['token']);
// переходим на index страницу
header("Location: index.php");
die();