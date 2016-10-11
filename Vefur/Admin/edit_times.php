<?php
	$pageName = 'Edit Times';

	$cid = (isset($_GET['cid']) ? $_GET['cid'] : -1);
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

				if ($cid === -1) {
					echo '<h3 class="error">ERROR! Classroom ID not set.</h3>';
				} else {
					$classroomName = '';

					$classroomNameQuery = "SELECT name FROM classrooms WHERE id=$cid LIMIT 1";
					$classroomNameRes = $db->prepare($classroomNameQuery);
					$classroomNameRes->execute();

					if ($classroomNameRes->rowCount() < 1) {
						echo '<h3 class="error">ERROR! Classroom not found.</h3>';
					} else {
						while ($row = $classroomNameRes->fetch(PDO::FETCH_ASSOC)) {
							$classroomName = $row['name'];
						}
					}

					$classroomNameRes = null;

					if ($classroomName !== '') {
						echo '<h2 class="title">Classroom '.$classroomName.'</h2>';

						if (isset($_POST['remove_time'])) {
							TimesClass::RemoveTime($db, $_POST['time_id']);
						}

						$timesRes = TimesClass::GetTimes($db, $cid);

						if (!is_array($timesRes)) {
							echo '<h2>'.$timesRes.'</h2>';
						} else {
							echo '<h3 class="title">Remove Time:</h3>';
							echo '<form action="" method="POST">';
							echo '<select name="time_id">';

							foreach ($timesRes as $key => $value) {
								echo '<option value="'.$value['time_id'].'">'.$value['day'].', From: '.$value['time_from'].', To: '.$value['time_to'].'</option>';
							}

							echo '</select>';
							echo '<input type="submit" name="remove_time" value="Remove">';
							echo '</form>';
						}
					}
				}
			}
		?>
	</body>
</html>