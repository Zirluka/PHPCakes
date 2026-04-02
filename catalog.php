<?php
// Подключаем header.php и передаём $title и $current_page
$title = "Каталог";
$current_page = "catalog";
require_once './template/header.php';

// проверяем id, если он есть, то показываем детальную страницу товара, 
// иначе общий каталог
$id = $_GET['id'] ?? null;
// Проверяем, авторизирован ли пользователь
$token = $_SESSION['token'] ?? null;
// Проверяем ToCart (товар для добавления в корзину)
$ToCart = $_POST['ToCart'] ?? null;
if ($ToCart) {
	// Проверяем на авторизацию
	if (!$token) {
		// Если токена нету, то отправляем пользователя на страницу входа
		header("Location: login.php");
		die();
	}
	// Если мы передали товар к добавлению, то проверяем, есть ли он уже в корзине
	if (isset($_SESSION['basket'][$ToCart])) {
		// Если есть, то просто прибавляем 1
		$_SESSION['basket'][$ToCart] += 1;
	} else {
		// Если нету, то создаём запись
		$_SESSION['basket'][$ToCart] = 1;
	}
}

// Если это не детальная страница товара
if ($id == null) {
	// Получаем страницу для пагинации, если её нету, то по дефолту записываем 1
	$page = $_GET['page'] ?? 1;
	// получаем лимит
	$limit = $page - 1;
	// Проверяем фильт
	$filter = $_GET['filter'] ?? null;
	
	if (!$filter) {
		// Если фильтра нету, то ищем все товары
		$query = mysqli_helper::get_select_query('*', 'goods', '1', '', '');
		// Смотрим сколько будет страниц для пагинации (На 1 странице 16 товаров)
		$count = mysqli_query($link, $query)->num_rows/16+1;
		// Даём запрос с лимитом где лимит - это $limit * 16, 16, 
		$query = mysqli_helper::add_limit($query, $limit * 16, 16);
		$mysqli_res = mysqli_query($link, $query);
		// Превращаем в массив
		$goods_arr = mysqli_helper::get_array($mysqli_res);
	} else {
		// Если есть фильтр, то ищем товары с оператором LIKE %$filter%
		$query = mysqli_helper::get_select_query('*', 'goods', 'name', 'LIKE', "'%$filter%'");
		// Считаем кол-во страниц и выполняем запрос
		$count = mysqli_query($link, $query)->num_rows/16+1;
		$query = mysqli_helper::add_limit($query, $limit * 16, 16);
		$mysqli_res = mysqli_query($link, $query);
		$goods_arr = mysqli_helper::get_array($mysqli_res);
	}

	

?>
<!-- Так как мы всё ещё находимся в блоке if ($id == null): 
	то данный блок html будет отображён только в случае, если нужен весь каталог -->
<main>
	<div class="catalog">
		<h1>Товары</h1>
		<!-- Фильтр -->
		<form method="get">
			<input type="text" name="filter">
			<button type="submit">Найти</button>
		</form>
		<div class="catalog_wrapper">
			<!-- Циклом проходимся по полученным записям и выводим их -->
			<?php foreach($goods_arr as $item): ?>
			<div class="catalog_item">
				<h2><?= $item['name'] ?></h2>
				<p><?= $item['description'] ?></p>
				<p><?= $item['price'] - $item['discount'] ?></p>
				<!-- В ссылке добавляем GET параметр id, для перехода на детальную страницу -->
				<a href="catalog.php?id=<?= $item['id'] ?>">Перейти</a>
			</div>
			<?php endforeach; ?>
		</div>
		<!-- Пагинация -->
		<div class="pagginator">
			<!-- Циклом проходимся на количество страниц -->
			<?php for($i = 1; $i <= $count; $i++): ?>
				<!-- Добавляем к ссылке GET параметры page (номер страницы) и filter (фильтр),
				 что-бы он не сбросился. Так же проверяем на какой странице мы 
				 находимся и выделяем нужный паггинатор -->
				<a href="catalog.php?page=<?= $i ?><?php if($filter):?><?= "&filter=$filter" ?> <?php endif; ?>" class="<?php if ($page == $i): ?> active <?php endif; ?>"><?= $i ?></a>
			<?php endfor; ?>
		</div>
	</div>
</main>

<?php } else {
	// Если у нас есть id товара, то мы выводим его детальную страницу
	// ищем товар 
	$query = mysqli_helper::get_select_query('*', 'goods', 'id', '=', $id);
	$goods = mysqli_fetch_assoc(mysqli_query($link, $query));
	// и его ингридиенты
	$query = mysqli_helper::get_select_query('*', 'goods_and_ingridients', 'goods_id', '=', $goods['id']);
	$mysqli_res = mysqli_query($link, $query);
	$g_a_i = mysqli_helper::get_array($mysqli_res);
	// для вывода ингридиентов создаём строку
	$ingridients = "";
	foreach($g_a_i as $item) {
		// проходимся циклом по ингридиентам, ищём их в бд и записываем в строку
		$query = mysqli_helper::get_select_query('name', 'Ingridients', 'id', '=', $item['ingridient_id']);
		$mysqli_res = mysqli_fetch_assoc(mysqli_query($link, $query));
		$ingridients .= $mysqli_res['name'].", ";
	}
	// Последние 2 символа убираем, т.к. это лишние точка с пробелом ", "
	$ingridients = substr($ingridients, 0, -2);
	

?>
<!-- Здесь выводим детальную страницу товара -->
<main>
	<but class="catalog_item id_item">
		<img src="./images/<?= $goods['img'] ?>" alt="">
		<h2>Название: <?= $goods['name'] ?></h2>
		<h3>Описание: <?= $goods['description'] ?></h3>
		<h3>Цена: <?= $goods['price'] - $goods['discount'] ?></h3>
		<p>Ингридиенты: <?= $ingridients ?></p>
		<!-- Форма для добавления товара в корзину -->
		<form method="post">
			<!-- При нажатии на кнопку улетает POST запрос, страница перезагружается
				и обрабатывает добавление -->
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