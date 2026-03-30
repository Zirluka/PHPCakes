<?php
// Подключаем header.php
$title = "Новости";
$current_page = "news";
require_once './template/header.php';

$id = $_GET['id'] ?? null;

if ($id == null) {
	$page = $_GET['page'] ?? 1;
	$limit = $page - 1;
	// Ищем все товары
	$query = mysqli_helper::get_select_query('*', 'news', '1', '', '');
	$count = mysqli_query($link, $query)->num_rows/16+1;
	$query = mysqli_helper::add_limit($query, $limit * 16, 16);
	$mysqli_res = mysqli_query($link, $query);
	$news_arr = mysqli_helper::get_array($mysqli_res);
	

?>

<main>
	<div class="catalog">
		<h1>Товары</h1>
		<div class="catalog_wrapper">
			<?php foreach($news_arr as $item): ?>
			<div class="catalog_item">
				<h2><?= $item['title'] ?></h2>
				<p><?= substr($item['text'], 0, 20).'...' ?></p>
				<a href="news.php?id=<?= $item['id'] ?>">Перейти</a>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="pagginator">
			<?php for($i = 1; $i <= $count; $i++): ?>
				<a href="news.php?page=<?= $i ?>" class="<?php if ($page == $i): ?> active <?php endif; ?>"><?= $i ?></a>
			<?php endfor; ?>
		</div>
	</div>
</main>

<?php } else {
	$query = mysqli_helper::get_select_query('*', 'news', 'id', '=', $id);
	$news = mysqli_fetch_assoc(mysqli_query($link, $query));

?>

<main>
	<h2><?= $news['title'] ?></h2>
	<h3><?= $news['text'] ?></h3>
</main>

<?php }?>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>