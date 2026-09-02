<?php
// Подключаем mysqli_helper
include 'mysqli_helper.php';

// Стартуем сессию
session_start();

// Подключаем Базу Данных
$dbHost = 'mysql';
$dbUser = 'cake_user';
$dbPass = 'cake_password';
$dbName = 'phpcakes_db';

$link = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or mysqli_connect_error();

/* 

	Функция dd() 
	Используется, для вывода в моменте,
	когда не нужно продолжать выполнение кода,
	например посмотреть правильность составления SQL запроса,
	но без его выполнения

*/
function dd($to_print) {
	echo '<pre>';
	print_r($to_print);
	echo '</pre>';

	die();
}