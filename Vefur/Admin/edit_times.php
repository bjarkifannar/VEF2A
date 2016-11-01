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
					$timesClass = new TimesClass();

					$classroomName = Classroom::GetNameFromID($db, $cid);

					if ($classroomName === 'Not Found') {
						echo '<h3 class="error">ERROR! Classroom not found.</h3>';
					} else {
						echo '<h2 class="title">Classroom '.$classroomName.'</h2>';

						if (isset($_POST['add_time'])) {
							$errorFlag = FALSE;

							$timeFrom = (isset($_POST['time_from']) ? $_POST['time_from'] : null);
							$timeTo = (isset($_POST['time_to']) ? $_POST['time_to'] : null);
							$dayID = (isset($_POST['day_id']) ? $_POST['day_id'] : null);

							if ($timeFrom === null || $timeTo === null || $dayID === null) {
								$errorFlag = TRUE;
							}

							if ($errorFlag) {
								echo '<h3 class="error">ERROR! Missing information</h3>';
							} else {
								$addTimeRes = $timesClass->AddTime($db, $cid, $timeFrom, $timeTo, $dayID);

								if (strtoupper($addTimeRes) === 'ERROR') {
									$addTimeErrors = $timesClass->GetErrorMsg();

									foreach ($addTimeErrors as $err) {
										echo '<h3 class="error">'.$err.'</h3>';
									}
								} else {
									echo '<h3 class="message">Time added</h3>';
								}
							}
						}

						if (isset($_POST['remove_time'])) {
							$timesClass->RemoveTime($db, $_POST['time_id']);
						}

						$timesRes = $timesClass->GetTimes($db, $cid);

						echo '<h3 class="title">Add Time:</h3>';
						echo '<form action="" method="POST">';
						echo '<label for="time_from">Time from:</label>';
						echo '<input type="text" name="time_from" id="time_from" required>';
						echo '<label for="time_to">Time to:</label>';
						echo '<input type="text" name="time_to" id="time_to" required>';
						echo '<label for="day_id">Day:</label>';
						echo '<select name="day_id" id="day_id">';

						$dayQuery = "SELECT id, name FROM days ORDER BY id ASC";
						$dayRes = $db->prepare($dayQuery);
						$dayRes->execute();

						while ($row = $dayRes->fetch(PDO::FETCH_ASSOC)) {
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
						}

						$dayQuery = $dayRes = null;

						echo '</select>';
						echo '<input type="submit" name="add_time" value="Add">';
						echo '</form>';

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