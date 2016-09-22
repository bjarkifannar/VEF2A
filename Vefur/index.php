<?php
	$pageName = 'Núna';

	$currentTime = date('H:i');
	$currentDayID = date('N');

	$numRows = 0;
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once 'inc/head.php'; ?>
	</head>
	<body>
		<?php
			require_once 'inc/header.php';
			require_once 'inc/db_connect.php';
		?>
		<h1 class="title">Lausar núna:</h1>
		<div class="flex-container">
		<?php
			$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to,
								days.name AS day_name
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
										INNER JOIN days
											ON times.day_id=days.id
												WHERE times.time_from < :cur_time
													AND times.time_to > :cur_time
													AND times.day_id=:day_id
												ORDER BY classrooms.name ASC";
			$queryRes = $db->prepare($query);
			$queryRes->bindParam(':cur_time', $currentTime);
			$queryRes->bindParam(':day_id', $currentDayID);
			$queryRes->execute();

			$numRows = $queryRes->rowCount();

			if ($numRows === 0) {
				echo '<h3>Engin gögn fundust</h3>';
			} else {
				while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="flex-item">';
					echo '<h3>'.$row['building_name'].' - '.$row['classroom_name'].'</h3>';
					echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
					echo '</div>';
				}
			}

			$queryRes = null;
		?>
		</div>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>
<?php $db = null; ?>