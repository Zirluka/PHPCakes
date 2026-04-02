<?php
// Подключаем header.php и передаём $title и $current_page
$title = "О нас";
$current_page = "about";
require_once './template/header.php';

?>

<main>
	<h1>Немного о нас</h1>
	<p>Что-то о компании</p>
</main>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>