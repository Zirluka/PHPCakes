<?php
// Подключаем mysqli_helper
include 'mysqli_helper.php';

// Стартуем сессию
session_start();

// Подключаем Базу Данных
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'cakes';

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