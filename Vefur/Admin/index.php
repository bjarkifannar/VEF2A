<?php
	$pageName = 'Admin';

	$noPermissionMessage = '<h3 class="title">You do not have permission to use this feature.</h3>';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once 'inc/head.php'; ?>
	</head>
	<body>
		<?php
			if ($logged === 'out') {
				header('Location: login.php');
			} else {
				require_once 'inc/header.php';

				$buildingQuery = "SELECT id,
										 name
											FROM buildings
										ORDER BY name ASC";
				$classroomQuery = "SELECT id,
											name
												FROM classrooms
													WHERE building_id=:building_id
												ORDER BY name ASC";
		?>
		<h2 class="title">Welcome <?php echo $_SESSION['username']; ?></h2>
		<?php
			if (isset($_POST['submit_classroom'])) {
				if (isset($_POST['classroom_name'], $_POST['building_id'])) {
					if ($_POST['building_id'] != "-1") {
						$addClassroom = new AddClassroom();
						$addClassroomRes = $addClassroom->InsertClassroom($db, $_POST['building_id'], $_POST['classroom_name']);

						if (!empty($addClassroomRes)) {
							for ($i = 0; $i < count($addClassroomRes); $i++) {
								echo '<h3 class="error">'.$addClassroomRes[$i].'</h3>';
							}
						}
					} else {
						echo '<h3 class="error">ERROR! You have to select a building.</h3>';
					}
				} else {
					echo '<h3 class="error">ERROR! Information missing.</h3>';
				}
			}

			if (isset($_POST['remove_classroom'])) {
				if (isset($_POST['classroom_name'], $_POST['building_id'])) {
					RemoveClassroom::Remove($db, $_POST['building_id'], $_POST['classroom_name']);
				}
			}

			if (isset($_POST['admin_full_name'], $_POST['admin_username'], $_POST['p'])) {
				$addAdmin = new AddAdmin($db, $_POST['admin_full_name'], $_POST['admin_username'], $_POST['p'], $_POST['admin_max_permission']);
				$addAdminRes = $addAdmin->InsertAdmin();

				if (!empty($addAdminRes)) {
					for ($i = 0; $i < count($addAdminRes); $i++) {
						echo '<h3 class="error">'.$addAdminRes[$i].'</h3>';
					}
				}
			}

			if (isset($_POST['remove_admin'], $_POST['admin_id'])) {
				$adminID = $_POST['admin_id'];

				if ($adminID !== '-1') {
					$removeAdminRes = RemoveAdmin::Remove($db, $adminID);
					echo '<h3 class="error">'.$removeAdminRes.'</h3>';
				} else {
					echo '<h3 class="error">You have to select and admin to remove.</h3>';
				}
			}

			if (isset($_POST['submit_building'])) {
				if (isset($_POST['building_name'])) {
					$buildingName = filter_input(INPUT_POST, 'building_name', FILTER_SANITIZE_STRING);

					$addBuildingRes = Building::Add($db, $buildingName);

					if (is_array($addBuildingRes)) {
						foreach ($addBuildingRes as $error) {
							echo '<h3 class="error">'.$error.'</h3>';
						}
					} else {
						echo '<h3 class="title">Building '.$buildingName.' added.</h3>';
					}
				} else {
					echo '<h3 class="error">ERROR! Building name missing</h3>';
				}
			}

			if (isset($_POST['remove_building'])) {
				if (isset($_POST['building_id'])) {
					$buildingID = $_POST['building_id'];

					if ($buildingID !== -1) {
						Building::Remove($db, $buildingID);
					}
				}
			}
		?>
		<div class="admin-flex-container">
			<div class="row admin-flex-item">
				<h2 class="title">Classroom list:</h2>
				<?php
					$buildingRes = $db->prepare($buildingQuery);
					$classroomRes = $db->prepare($classroomQuery);
					$buildingRes->execute();
					
					while ($row = $buildingRes->fetch(PDO::FETCH_ASSOC)) {
						echo '<h3>'.$row['name'].'</h3>';

						$classroomRes->bindParam(':building_id', $row['id']);
						$classroomRes->execute();

						if ($classroomRes->rowCount() === 0) {
							echo 'No classrooms found.';
						} else {
							while ($row2 = $classroomRes->fetch(PDO::FETCH_ASSOC)) {
								echo '<a href="edit_times.php?cid='.$row2['id'].'">'.trim($row2['name']).'</a>. ';
							}
						}
					}

					$classroomRes = $buildingRes = null;
				?>
			</div>
			<div class="row admin-flex-item">
				<h2 class="title">Add classroom:</h2>
				<?php
					if ($_SESSION['has_max_permission'] === '1') {
				?>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label>Classroom name:</label>
					<input type="text" name="classroom_name" required>
					<label>Building:</label>
					<select name="building_id" required>
						<option value="-1" selected disabled>Select Building</option>
						<?php
							$buildingRes = $db->prepare($buildingQuery);
							$buildingRes->execute();

							while ($row = $buildingRes->fetch(PDO::FETCH_ASSOC)) {
								echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
							}

							$buildingRes = null;
						?>
					</select>
					<input type="submit" name="submit_classroom" value="Add">
				</form>
				<a href="upload_file.php">Upload File</a>
				<?php
					} else {
						echo $noPermissionMessage;
					}
				?>
			</div>
			<div class="row admin-flex-item">
				<h2 class="title">Remove classroom:</h2>
				<?php
					if ($_SESSION['has_max_permission'] === '1') {
				?>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label>Classroom name:</label>
					<input type="text" name="classroom_name" required>
					<label>Building:</label>
					<select name="building_id" required>
						<option value="-1" selected disabled>Select Building</option>
						<?php
							$buildingRes = $db->prepare($buildingQuery);
							$buildingRes->execute();

							while ($row = $buildingRes->fetch(PDO::FETCH_ASSOC)) {
								echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
							}

							$buildingRes = null;
						?>
					</select>
					<input type="submit" name="remove_classroom" value="Remove">
				</form>
				<?php
					} else {
						echo $noPermissionMessage;
					}
				?>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Building list:</h2>
				<?php
					$buildingRes = $db->prepare($buildingQuery);
					$buildingRes->execute();

					while ($row = $buildingRes->fetch(PDO::FETCH_ASSOC)) {
						echo $row['name'].'<br>';
					}

					$buildingRes = null;
				?>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Add building:</h2>
				<?php
					if ($_SESSION['has_max_permission'] === '1') {
				?>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label>Building name:</label>
					<input type="text" name="building_name" required>
					<input type="submit" name="submit_building" value="Add">
				</form>
				<?php
					} else {
						echo $noPermissionMessage;
					}
				?>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Remove building:</h2>
				<?php
					if ($_SESSION['has_max_permission'] === '1') {
				?>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label for="building_id">Building:</label>
					<select name="building_id" id="building_id">
						<option value="-1" disabled selected>Select building</option>
						<?php
							$buildingRes = $db->prepare($buildingQuery);
							$buildingRes->execute();

							while ($row = $buildingRes->fetch(PDO::FETCH_ASSOC)) {
								echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
							}

							$buildingRes = null;
						?>
					</select>
					<input type="submit" name="remove_building" value="Remove">
				</form>
				<?php
					} else {
						echo $noPermissionMessage;
					}
				?>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Admin list:</h2>
				<?php
					$adminQuery = "SELECT id, full_name, username FROM admins ORDER BY full_name ASC";
					$adminRes = $db->prepare($adminQuery);
					$adminRes->execute();

					while ($row = $adminRes->fetch(PDO::FETCH_ASSOC)) {
						echo '<a href="edit_admin.php?aid='.$row['id'].'">'.$row['username'].'</a>: '.$row['full_name'].'<br>';
					}

					$adminRes = null;
				?>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Add admin:</h2>
				<?php
					if ($_SESSION['has_max_permission'] === '1') {
				?>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label>Full Name:</label>
					<input type="text" name="admin_full_name" required>
					<label>Username:</label>
					<input type="text" name="admin_username" required>
					<label>Password:</label>
					<input type="password" name="admin_pwd" required>
					<label>Has maximum permission:</label>
					<select name="admin_max_permission" required>
						<option value="1">Yes</option>
						<option value="0" selected>No</option>
					</select>
					<input type="button" name="submit_admin" onclick="formhash(this.form, this.form.admin_pwd);" value="Add">
				</form>
				<?php
					} else {
						echo $noPermissionMessage;
					}
				?>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Remove Admin</h2>
				<?php
					if ($_SESSION['has_max_permission'] === '1') {
				?>
				<form action="" method="POST">
					<label for="admin_id">Admin:</label>
					<select name="admin_id" id="admin_id" required>
						<option value="-1" disabled selected>Select admin</option>
				<?php
						$adminQuery = "SELECT id, full_name, username FROM admins";
						$adminRes = $db->prepare($adminQuery);
						$adminRes->execute();

						while ($row = $adminRes->fetch(PDO::FETCH_ASSOC)) {
							if ($_SESSION['user_id'] !== $row['id']) {
								echo '<option value="'.$row['id'].'">'.$row['username'].': '.$row['full_name'].'</option>';
							}
						}

						$adminQuery = $adminRes = null;
				?>
					</select>
					<input type="submit" name="remove_admin" value="Remove">
				</form>
				<?php
					} else {
						echo $noPermissionMessage;
					}
				?>
			</div>
		</div>
		<?php
			}
		?>
		<?php require_once 'inc/footer.php'; ?>
		<script type="text/javascript" src="../js/sha512.js"></script>
		<script type="text/javascript" src="../js/forms.js"></script>
	</body>
</html>