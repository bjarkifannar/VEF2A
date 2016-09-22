<?php
	require_once 'core/init.php';
	require_once '../inc/db_connect.php';

	$pageName = 'Admin - Login';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once 'inc/head.php'; ?>
	</head>
	<body>
		<?php require_once 'inc/header.php'; ?>
		<h1 class="title">Log in as admin</h1>
		<?php
			if ($logged === 'in') {
				header('Location: index.php');
			} else {
				if (isset($_POST['username']) && isset($_POST['p'])) {
					$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
					$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

					if (login($username, $password, $db) === "Success") {
						header('Location: index.php');
					} else {
						echo '<h3  class="error">ERROR! Could not log you in.</h3>';
					}
				} else {
		?>
		<div class="login-form">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<label for="username">Username:</label>
				<input type="text" name="username" placeholder="Username *" id="username">
				<label for="pwd">Password:</label>
				<input type="password" name="pwd" placeholder="Password *" id="pwd">
				<input type="button" value="Login" onclick="formhash(this.form, this.form.pwd);">
			</form>
			<p class="required-message">* Required</p>
		</div>
		<?php
				}
			}
		?>
		<?php require_once 'inc/footer.php'; ?>
		<script type="text/javascript" src="../js/sha512.js"></script>
		<script type="text/javascript" src="../js/forms.js"></script>
	</body>
</html>