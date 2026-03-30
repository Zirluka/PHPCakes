<?php
// Подключаем header.php
$title = "Регистрация";
$current_page = "register";
require_once './template/header.php';

if (isset($_SESSION['token'])) {
	header("Location: index.php");
	die();
}

$email = $_POST['email'] ?? null;
$login = $_POST['login'] ?? null;
$name = $_POST['name'] ?? null;
$password = $_POST['password'] ?? null;

if ($email && $login && $name && $password) {
	// Ищем по почте
	$query = mysqli_helper::get_select_query('id', 'users', 'email', '=', "'$email'");
	$res = mysqli_fetch_assoc(mysqli_query($link, $query));
	if ($res) {
		$_SESSION['error']['register']['email'] = "Email already taken";
		$_SESSION['old_input'] = $_POST;
		header("Location: register.php");
		die();
	}

	unset($_SESSION['error']);
	unset($_SESSION['old_input']);
	$password = password_hash($password, PASSWORD_DEFAULT);
	$query = mysqli_helper::get_insert_query("users", "`email`, `login`, `name`, `password`", "'$email', '$login', '$name', '$password'");
	mysqli_query($link, $query);
	header("Location: login.php");
	die();
}

?>

<main class="auth_page">
	<form method="post">
		<label>Email</label>
		<input type="email" name="email" placeholder="email" required
			class="<?php if($_SESSION['error']['register']['email']): ?> is-invalid <?php endif; ?>"
				value="<?php echo $_SESSION['old_input']['email'] ?? "" ?>">
		<?php if($_SESSION['error']['register']['email']): ?>
			<p class="is-invalid"><?= $_SESSION['error']['register']['email'] ?></p>
		<?php endif ?>

		<label>Login</label>
		<input type="text" name="login" placeholder="login" required
			value="<?php echo $_SESSION['old_input']['login'] ?? "" ?>">
		
		<label>Name</label>
		<input type="text" name="name" placeholder="name" required
			value="<?php echo $_SESSION['old_input']['name'] ?? "" ?>"> 
		
		<label>Password</label>
		<input type="password" name="password" placeholder="password" required>

		<button type="submit">Регистрация</button>
		
	</form>
</main>

<?php
// Подключаем footer.php
require_once './template/footer.php';
?>