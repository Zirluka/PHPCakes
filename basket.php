<?php
// Подключаем header.php и передаём $title и $current_page
$title = "Корзина";
$current_page = "basket";
require_once './template/header.php';

// Проверяем, авторизирован ли пользователь
$token = $_SESSION['token'] ?? null;
if (empty($token)) {
	// Если нет, то отправляем его на страницу входа
	header("Location: login.php");
	die();
}

// Проверяем корзину
$basket = $_SESSION['basket'] ?? null;
// Проверяем на наличии каких-либо действий с корзиной,
// Для этого далее используем POST форму, параметр - это товар
$plus = $_POST['plus'] ?? null;
$minus = $_POST['minus'] ?? null;
$delete = $_POST['delete'] ?? null;

if ($plus) {
	// Если действие добавить штуку товару, то добавляем, доп. проверок не нужно
	$_SESSION['basket'][$plus] += 1;
	// Обновляем страницу для подгрузки сессии
	header("Location: basket.php");
	die();
}
if ($minus) {
	// Если мы ловим действие на отнятие шт из товара корзины
	// То проверяем, сколько товара было
	if ($_SESSION['basket'][$minus] == 1) {
		// Если оставался всего 1 товар, то мы убираем его из сессии полностью
		unset($_SESSION['basket'][$minus]);
		header("Location: basket.php");
		die();
	}
	// Иначе просто отнимаем 1
	$_SESSION['basket'][$minus] -= 1;
	header("Location: basket.php");
	die();
}
if ($delete) { 
	// Тут просто удаляем товар из корзины и обновляем страницу
	unset($_SESSION['basket'][$delete]);
	header("Location: basket.php");
	die();
}

if ($basket) {
	// Если корзина есть, то перебираем её и собираем вывод 
	// (В корзине хранятся только id и количество товара)
	$basket_items = [];
	foreach($basket as $key => $item) {
		$query = mysqli_helper::get_select_query('*', 'goods', 'id','=',$key);
		$mysqli_res = mysqli_fetch_assoc(mysqli_query($link, $query));
		$basket_items[$key] = ['name' => $mysqli_res, 'count' => $item];
	}
}

?>

<main class="basket">
	<table class="bakset_wrapper">
			<tr>
				<td>id</td>
				<td>name</td>
				<td>count</td>
				<td>plus</td>
				<td>minus</td>
				<td>delete</td>
			</tr>
			<!-- Циклом перебираем все товары -->
			<?php foreach($basket_items as $key => $item): ?>
			<tr>
				<td><?= $key ?></td>
				<td><?= $item['name'] ?></td>
				<td><?= $item['count'] ?></td>
				<td>
					<!-- Форма для добавления шт. товару -->
					<form method="post">
						<input type="hidden" name="plus" value="<?= $key ?>">
						<button type="submit">plus</button>
					</form>
				</td>
				<td>
					<!-- Форма для добавления шт. товару -->
					<form method="post">
						<input type="hidden" name="minus" value="<?= $key ?>">
						<button type="submit">minus</button>
					</form>
				</td>
				<td>
					<!-- Форма для удаления товара из корзины -->
					<form method="post">
						<input type="hidden" name="delete" value="<?= $key ?>">
						<button type="submit">delete</button>
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</table>
</main>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>