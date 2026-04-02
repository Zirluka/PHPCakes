<?php
// Подключаем header.php
$title = "Авторизация";
$current_page = "login";
require_once './template/header.php';

// Если у пользователя уже есть токен, то перенаправляем его на индексную страницу
if (isset($_SESSION['token'])) {
	header("Location: index.php");
	die();
}
// Ловим почту и пароль
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if ($email && $password) {
	// Если почта и пароль нашлись, то проверяем, есть ли пользователь с такой почтой
	$query = mysqli_helper::get_select_query('*', 'users', 'email', '=', "'$email'");
	$res = mysqli_fetch_assoc(mysqli_query($link, $query));
	if (!$res) {
		// Если пользователь не найден, выбрасиваем ошибку
		$_SESSION['error']['login']['email'] = "Email or password is incorrect";
		$_SESSION['old_input'] = $_POST;
		header("Location: /login.php");
		exit;
	}
	if (!password_verify($password, $res['password'])) {
		// Если пользователь нашёлся, но пароль не подошёл, точно так же выбрасиваем ошибку
		$_SESSION['error']['login']['email'] = "Email or password is incorrect";
		$_SESSION['old_input'] = $_POST;
		header("Location: /login.php");
		exit;
	}

	// Если всё подошло, то убираем ошибки и старые инпуты
	unset($_SESSION['error']);
	unset($_SESSION['old_input']);
	// Создаём случайный токен
	$token = bin2hex(random_bytes(24));
	// Отправляем этот токен пользователю в бд
	$query = mysqli_helper::get_update_query('users', "`token`='$token'", 'id', '=', $res['id']);
	mysqli_query($link, $query);
	// Записываем токен в сессию и перебрасываем пользователя на индексную страницу
	$_SESSION['token'] = $token;
	header("Location: /index.php");
	exit;
}

?>

<main class="auth_page">
	<form method="post">
		<label>Email</label>
		<!-- Выводим ошибки через классы is-invalid -->
		<input type="email" name="email" placeholder="email" required
			class="<?php if($_SESSION['error']['login']['email']): ?> is-invalid <?php endif; ?>"
				value="<?php echo $_SESSION['old_input']['email'] ?? "" ?>">
		<?php if($_SESSION['error']['login']['email']): ?>
			<p class="is-invalid"><?= $_SESSION['error']['login']['email'] ?></p>
		<?php endif ?>

		<label>Password</label>
		<input type="password" name="password" placeholder="password" required>

		<button type="submit">Авторизация</button>
		
	</form>
</main>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>