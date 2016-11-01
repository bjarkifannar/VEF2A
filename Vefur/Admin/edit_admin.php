<?php
	$pageName = 'Admin - Edit Admin';

	$adminID = (isset($_GET['aid']) ? $_GET['aid'] : null);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once 'inc/head.php'; ?>
	</head>
	<body>
		<?php require_once 'inc/header.php'; ?>
		<?php
			if ($logged !== 'in') {
				header('Location: index.php');
			} else {
				if ($_SESSION['has_max_permission'] === '1') {
					if ($adminID === null) {
						header('Location: index.php');
					} else {
						if (isset($_POST['edit_admin'], $_POST['admin_full_name'], $_POST['admin_permission'])) {
							$editClass = new EditAdmin($db, $adminID, $_POST['admin_full_name'], $_POST['admin_permission']);
						}

						$adminQuery = "SELECT username, full_name, has_max_permission FROM admins WHERE id=:admin_id LIMIT 1";
						$adminRes = $db->prepare($adminQuery);
						$adminRes->bindParam(':admin_id', $adminID);
						$adminRes->execute();

						if ($adminRes->rowCount() === 0) {
							echo '<h3 class="message">Could not find an admin with that ID.</h3>';
						} else {
							while ($row = $adminRes->fetch(PDO::FETCH_ASSOC)) {
		?>
		<h2 class="title"><?php echo $row['username']; ?></h2>
		<form action="" method="POST">
			<label>Full name:</label>
			<input type="text" name="admin_full_name" value="<?php echo $row['full_name']; ?>" required>
			<label>Has full permission</label>
			<select name="admin_permission">
				<?php
					if ($row['has_max_permission'] === '1') {
						echo '<option value="1" selected>Yes</option>';
						echo '<option value="0">No</option>';
					} else {
						echo '<option value="1">Yes</option>';
						echo '<option value="0" selected>No</option>';
					}
				?>
			</select>
			<input type="submit" name="edit_admin" value="Edit">
		</form>
		<?php
							}
						}

						$adminQuery = $adminRes = null;
					}
				} else {
					header('Location: index.php');
				}
			}
		?>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>