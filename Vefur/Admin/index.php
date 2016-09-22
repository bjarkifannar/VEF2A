<?php
	require_once 'core/init.php';
	require_once '../inc/db_connect.php';

	$pageName = 'Admin';
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
				$classroomQuery = "SELECT name
											FROM classrooms
												WHERE building_id=:building_id
											ORDER BY name ASC";
		?>
		<h2 class="title">Welcome <?php echo $_SESSION['username']; ?></h2>
		<?php
			if (isset($_POST['submit_classroom'])) {
				if (isset($_POST['classroom_name'], $_POST['building_id'])) {
					if ($_POST['building_id'] != "-1") {
						$exists = FALSE;
						$classroomName = $_POST['classroom_name'];
						$buildingID = $_POST['building_id'];

						$checkClassroomQuery = "SELECT name FROM classrooms WHERE building_id=:building_id";
						$checkClassroomRes = $db->prepare($checkClassroomQuery);
						$checkClassroomRes->bindParam(':building_id', $buildingID);
						$checkClassroomRes->execute();

						while ($row = $checkClassroomRes->fetch(PDO::FETCH_ASSOC)) {
							if ($row['name'] === $classroomName) {
								echo '<h3 class="error">ERROR! Classroom is already in the database.</h3>';
								$exists = TRUE;
							}
						}

						$checkClassroomRes = null;

						if (!$exists) {
							$insertClassroomQuery = "INSERT INTO classrooms (building_id, name)
																	VALUES (:building_id, :classroom_name)";
							$insertClassroomRes = $db->prepare($insertClassroomQuery);
							$insertClassroomRes->bindParam(':building_id', $buildingID);
							$insertClassroomRes->bindParam(':classroom_name', $classroomName);

							if ($insertClassroomRes->execute()) {
								echo '<h3>Classroom '.$classroomName.' added.</h3>';
							} else {
								echo '<h3 class="error">ERROR! Could not add classroom</h3>';
							}
						}
					} else {
						echo '<h3 class="error">ERROR! You have to select a building.</h3>';
					}
				} else {
					echo '<h3 class="error">ERROR! Information missing.</h3>';
				}
			}

			if (isset($_POST['admin_full_name'], $_POST['admin_username'], $_POST['p'])) {
				$fullName = filter_input(INPUT_POST, 'admin_full_name', FILTER_SANITIZE_STRING);
				$username = filter_input(INPUT_POST, 'admin_username', FILTER_SANITIZE_STRING);
				$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
				$errorFlag = FALSE;

				$checkAdminQuery = "SELECT id FROM admins WHERE username=:admin_username LIMIT 1";
				$checkAdminRes = $db->prepare($checkAdminQuery);
				$checkAdminRes->bindParam(':admin_username', $username);
				$checkAdminRes->execute();

				if ($checkAdminRes->rowCount() > 0) {
					echo '<h3 class="error">ERROR! The admin username is already taken.</h3>';
					$errorFlag = TRUE;
				}

				$checkAdminRes = null;

				if (!$errorFlag) {
					$insertAdminQuery = "INSERT INTO admins (full_name, username, password) VALUES (:full_name, :username, :password)";
					$insertAdminRes = $db->prepare($insertAdminQuery);
					$insertAdminRes->bindParam(':full_name', $fullName);
					$insertAdminRes->bindParam(':username', $username);
					$insertAdminRes->bindParam(':password', $password);

					if ($insertAdminRes->execute()) {
						echo '<h3>Admin added to database.</h3>';
					} else {
						echo '<h3 class="error">ERROR! Could not add admin to database</h3>';
					}

					$insertAdminRes = null;
				}
			}

			if (isset($_POST['submit_building'])) {
				if (isset($_POST['building_name'])) {
					$buildingName = filter_input(INPUT_POST, 'building_name', FILTER_SANITIZE_STRING);
					$errorFlag = FALSE;

					$checkBuildingQuery = "SELECT id FROM buildings WHERE name=:name LIMIT 1";
					$checkBuildingRes = $db->prepare($checkBuildingQuery);
					$checkBuildingRes->bindParam(':name', $buildingName);
					$checkBuildingRes->execute();

					if ($checkBuildingRes->rowCount() > 0) {
						$errorFlag = TRUE;
						echo '<h3 class="error">ERROR! This building is already in the database</h3>';
					}

					$checkBuildingRes = null;

					if (!$errorFlag) {
						$insertBuildingQuery = "INSERT INTO buildings (name) VALUES (:name)";
						$insertBuildingRes = $db->prepare($insertBuildingQuery);
						$insertBuildingRes->bindParam(':name', $buildingName);

						if(!$insertBuildingRes->execute()) {
							echo '<h3 class="error">ERROR! Could not insert the building into the database</h3>';
						}
					}
				} else {
					echo '<h3 class="error">ERROR! Building name missing</h3>';
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
								echo $row2['name'].'. ';
							}
						}
					}

					$classroomRes = null;
					$buildingRes = null;
				?>
			</div>
			<div class="row admin-flex-item">
				<h2 class="title">Add classroom:</h2>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label for="classroom_name">Classroom name:</label>
					<input type="text" name="classroom_name">
					<label for="building_id">Building:</label>
					<select name="building_id">
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
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Add building:</h2>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label for="building_name">Building name:</label>
					<input type="text" name="building_name" id="building_name">
					<input type="submit" name="submit_building" value="Add">
				</form>
			</div>
			<div class="row admin-flex-item no-border">
				<h2 class="title">Add admin:</h2>
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<label for="admin_full_name">Full Name:</label>
					<input type="text" name="admin_full_name" required>
					<label for="admin_username">Username:</label>
					<input type="text" name="admin_username">
					<label for="admin_pwd">Password:</label>
					<input type="password" name="admin_pwd">
					<input type="button" name="submit_admin" onclick="formhash(this.form, this.form.admin_pwd);" value="Add">
				</form>
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