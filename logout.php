<?php
// Подключаем header.php
$title = "Выход";
$current_page = "logout";
require_once './template/header.php';

$token = $_SESSION['token'] ?? null;

if (!$token) {
	header("Location: login.php");
	die();
}

$query = mysqli_helper::get_update_query("users", "`token`=null", 'token','=',"'$token'");
mysqli_query($link, $query);

unset($_SESSION['token']);

header("Location: index.php");
die();