<?php
// Подключаем header.php и $title и $current_page
$title = "Контакты";
$current_page = "contacts";
require_once './template/header.php';

?>

<main>
	<h1>Наши контакты</h1>
	<p>email: email@email.com</p>
	<p>Телефон: +7 (xxx) xxx-xx-xx</p>
</main>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>