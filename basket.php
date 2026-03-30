<?php
// Подключаем header.php
$title = "Корзина";
$current_page = "basket";
require_once './template/header.php';

$basket = $_SESSION['basket'] ?? null;

$plus = $_POST['plus'] ?? null;
$minus = $_POST['minus'] ?? null;
$delete = $_POST['delete'] ?? null;

if ($plus) {
	$_SESSION['basket'][$plus] += 1;
	header("Location: basket.php");
	die();
}
if ($minus) {
	if ($_SESSION['basket'][$minus] == 1) {
		unset($_SESSION['basket'][$minus]);
		header("Location: basket.php");
		die();
	}
	$_SESSION['basket'][$minus] -= 1;
	header("Location: basket.php");
	die();
}
if ($delete) {
	unset($_SESSION['basket'][$delete]);
	header("Location: basket.php");
	die();
}

if ($basket) {
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
			<?php foreach($basket_items as $key => $item): ?>
			<tr>
				<td><?= $key ?></td>
				<td><?= $item['name'] ?></td>
				<td><?= $item['count'] ?></td>
				<td>
					<form method="post">
						<input type="hidden" name="plus" value="<?= $key ?>">
						<button type="submit">plus</button>
					</form>
				</td>
				<td>
					<form method="post">
						<input type="hidden" name="minus" value="<?= $key ?>">
						<button type="submit">minus</button>
					</form>
				</td>
				<td>
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