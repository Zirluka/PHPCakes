<?php
// Подключаем header.php
$title = "Каталог";
$current_page = "catalog";
require_once './template/header.php';

$id = $_GET['id'] ?? null;

$ToCart = $_POST['ToCart'] ?? null;
if ($ToCart) {
	if (isset($_SESSION['basket'][$ToCart])) {
		$_SESSION['basket'][$ToCart] += 1;
	} else {
		$_SESSION['basket'][$ToCart] = 1;
	}
}


if ($id == null) {
	$page = $_GET['page'] ?? 1;
	$limit = $page - 1;
	
	$filter = $_GET['filter'] ?? null;
	
	if (!$filter) {
		// Ищем все товары
		$query = mysqli_helper::get_select_query('*', 'goods', '1', '', '');
		$count = mysqli_query($link, $query)->num_rows/16+1;
		$query = mysqli_helper::add_limit($query, $limit * 16, 16);
		$mysqli_res = mysqli_query($link, $query);
		$goods_arr = mysqli_helper::get_array($mysqli_res);
	} else {
		// Ищем все товары
		$query = mysqli_helper::get_select_query('*', 'goods', 'name', 'LIKE', "'%$filter%'");
		$count = mysqli_query($link, $query)->num_rows/16+1;
		$query = mysqli_helper::add_limit($query, $limit * 16, 16);
		$mysqli_res = mysqli_query($link, $query);
		$goods_arr = mysqli_helper::get_array($mysqli_res);
	}

	

?>

<main>
	<div class="catalog">
		<h1>Товары</h1>
		<form method="get">
			<input type="text" name="filter">
			<button type="submit">Найти</button>
		</form>
		<div class="catalog_wrapper">
			<?php foreach($goods_arr as $item): ?>
			<div class="catalog_item">
				<h2><?= $item['name'] ?></h2>
				<p><?= $item['description'] ?></p>
				<p><?= $item['price'] - $item['discount'] ?></p>
				<a href="catalog.php?id=<?= $item['id'] ?>">Перейти</a>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="pagginator">
			<?php for($i = 1; $i <= $count; $i++): ?>
				<a href="catalog.php?page=<?= $i ?><?php if($filter):?><?= "&filter=$filter" ?> <?php endif; ?>" class="<?php if ($page == $i): ?> active <?php endif; ?>"><?= $i ?></a>
			<?php endfor; ?>
		</div>
	</div>
</main>

<?php } else {
	$query = mysqli_helper::get_select_query('*', 'goods', 'id', '=', $id);
	$goods = mysqli_fetch_assoc(mysqli_query($link, $query));

	$query = mysqli_helper::get_select_query('*', 'goods_and_ingridients', 'goods_id', '=', $goods['id']);
	$mysqli_res = mysqli_query($link, $query);
	$g_a_i = mysqli_helper::get_array($mysqli_res);

	$ingridients = "";
	foreach($g_a_i as $item) {
		$query = mysqli_helper::get_select_query('name', 'Ingridients', 'id', '=', $item['ingridient_id']);
		$mysqli_res = mysqli_fetch_assoc(mysqli_query($link, $query));
		$ingridients .= $mysqli_res['name'].", ";
	}
	$ingridients = substr($ingridients, 0, -2);
	

?>

<main>
	<but class="catalog_item id_item">
		<img src="./images/<?= $goods['img'] ?>" alt="">
		<h2>Название: <?= $goods['name'] ?></h2>
		<h3>Описание: <?= $goods['description'] ?></h3>
		<h3>Цена: <?= $goods['price'] - $goods['discount'] ?></h3>
		<p>Ингридиенты: <?= $ingridients ?></p>
		<form method="post">
			<input type="hidden" value="<?= $goods['id'] ?>" name="ToCart">
			<button type="submit">В корзину</button>
		</form>
	</b>
</main>

<?php }?>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>