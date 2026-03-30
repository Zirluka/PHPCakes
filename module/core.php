<?php
include 'mysqli_helper.php';

// Стартуем сессию
session_start();

// Подключаем Базу Данных
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'cakes';

$link = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or mysqli_connect_error();

function dd($to_print) {
	echo '<pre>';
	print_r($to_print);
	echo '</pre>';

	die();
}