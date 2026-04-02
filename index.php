<?php
// Подключаем header.php $title и $current_page
$title = "Главная страница";
$current_page = "index";
require_once './template/header.php';
// Если нам прислали Email, то выводим, что успешно подписались на рассылку
$email = $_POST['email'] ?? null;
if ($email) {
	echo '<script>alert("Вы успешно подписались на рассылку!");</script>';
}

// Ищем акционные товары
$query = mysqli_helper::get_select_query('*', 'goods', 'discount', '>', 0);
$discount = mysqli_query($link, $query);
// Перебираем
$discount_array = mysqli_helper::get_array($discount);
// Если товаром меньше чем 4, то делаем $i не 0, а равным количеству товаров
$discount_length = count($discount_array) - 1;
$discount_max = $discount_length >= 3 ? 4 : $discount_length + 1;

// Ищем новости
$query = mysqli_helper::get_select_query('*', 'news', 1,'','');
$news = mysqli_query($link, $query);
// Перебираем
$news_array = mysqli_helper::get_array($news);
// Если товаром меньше чем 4, то делаем $i не 0, а равным количеству товаров
$news_length = count($news_array) - 1;
$news_max = $news_length >= 3 ? 4 : $news_length + 1;

// Ищем сотрудников
$query = mysqli_helper::get_select_query('*', 'users', 'role_id', '=', 3);
$employee = mysqli_query($link, $query);
// Перебираем
$employee_array = mysqli_helper::get_array($employee);


?>

<main>
	<!-- Акционные товары -->
	<section class="discount">
		<h2>Акционные товары</h2>
		<div class="grid_wrapper">
			<!-- Проходимся циклом и выводим последние 4 акционных товара -->
			<?php for($disc_i = 0; $disc_i < $discount_max; $disc_i++):?>
			<div class="grid_item">
				<h3><?= $discount_array[$discount_length - $disc_i]['name'] ?></h3>
				<p><?= $discount_array[$discount_length - $disc_i]['description'] ?></p>
				<p><?= $discount_array[$discount_length - $disc_i]['price'] - $discount_array[$discount_length - $disc_i]['discount'] ?></p>
			</div>
			<?php endfor; ?>
		</div>
	</section>
	<!-- Последние новости -->
	<section class="news">
		<h2>Последние новости</h2>
		<div class="grid_wrapper">
			<!-- Проходимся циклом и выводим последние 4 новости -->
			<?php for($news_i = 0; $news_i < $news_max; $news_i++): ?>
			<div class="grid_item">
				<h3><?= $news_array[$news_length - $news_i]['title'] ?></h3>
				<p><?= $news_array[$news_length - $news_i]['text'] ?></p>
			</div>
			<?php endfor; ?>
		</div>
	</section>
	<!-- Сотрудники -->
	<section class="employees">
		<h2>Сотрудники</h2>
		<div class="grid_wrapper">
			<!-- Выводим всех сотрудников -->
			<?php foreach($employee_array as $item): ?>
			<div class="grid_item">
				<h3><?= $item['name'] ?></h3>
				<p><?= $item['email'] ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</section>
	<section class="senders">
		<h2>Рассылка</h2>
		<!-- Форма для подписи на рассылку, отправляем email наверх и обрабатываем -->
		<form method="post">
			<label>Email</label>
			<input type="email" name="email" placeholder="Email" required>
			<button type="submit">Подписаться на рассылку</button>
		</form>
	</section>

</main>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>