<?php
	$pageName = 'Allt';

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
		<h1 class="title">Allir tímar</h1>
		<h2>Mánudagar:</h2>
		<div class="times-container">
		<?php
			$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
												WHERE times.day_id=1
											ORDER BY times.time_from ASC";
			$queryRes = $db->prepare($query);
			$queryRes->execute();

			$numRows = $queryRes->rowCount();

			if ($numRows === 0) {
				echo '<h3>Engin gögn fundust</h3>';
			} else {
				while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="time-div">';
					echo '<h4>'.$row['building_name'].' - '.$row['classroom_name'].'</h4>';
					echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
					echo '</div>';
				}
			}
			
			$queryRes = null;
		?>
		</div>
		<h2>Þriðjudagar:</h2>
		<div class="times-container">
		<?php
			$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
												WHERE times.day_id=2
											ORDER BY times.time_from ASC";
			$queryRes = $db->prepare($query);
			$queryRes->execute();

			$numRows = $queryRes->rowCount();

			if ($numRows === 0) {
				echo '<h3>Engin gögn fundust</h3>';
			} else {
				while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="time-div">';
					echo '<h4>'.$row['building_name'].' - '.$row['classroom_name'].'</h4>';
					echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
					echo '</div>';
				}
			}

			$queryRes = null;
		?>
		</div>
		<h2>Miðvikudagar:</h2>
		<div class="times-container">
		<?php
			$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
												WHERE times.day_id=3
											ORDER BY times.time_from ASC";
			$queryRes = $db->prepare($query);
			$queryRes->execute();

			$numRows = $queryRes->rowCount();

			if ($numRows === 0) {
				echo '<h3>Engin gögn fundust</h3>';
			} else {
				while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="time-div">';
					echo '<h4>'.$row['building_name'].' - '.$row['classroom_name'].'</h4>';
					echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
					echo '</div>';
				}
			}

			$queryRes = null;
		?>
		</div>
		<h2>Fimmtudagar:</h2>
		<div class="times-container">
		<?php
			$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
												WHERE times.day_id=4
											ORDER BY times.time_from ASC";
			$queryRes = $db->prepare($query);
			$queryRes->execute();

			$numRows = $queryRes->rowCount();

			if ($numRows === 0) {
				echo '<h3>Engin gögn fundust</h3>';
			} else {
				while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="time-div">';
					echo '<h4>'.$row['building_name'].' - '.$row['classroom_name'].'</h4>';
					echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
					echo '</div>';
				}
			}

			$queryRes = null;
		?>
		</div>
		<h2>Föstudagar:</h2>
		<div class="times-container">
		<?php
			$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
												WHERE times.day_id=5
											ORDER BY times.time_from ASC";
			$queryRes = $db->prepare($query);
			$queryRes->execute();

			$numRows = $queryRes->rowCount();

			if ($numRows === 0) {
				echo '<h3>Engin gögn fundust</h3>';
			} else {
				while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="time-div">';
					echo '<h4>'.$row['building_name'].' - '.$row['classroom_name'].'</h4>';
					echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
					echo '</div>';
				}
			}

			$queryRes = null;
		?>
		</div>
		<br>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>
<?php $db = null; ?>