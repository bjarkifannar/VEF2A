<?php
	$pageName = 'Allt';

	$days = ['Mánudagar', 'Þriðjudagar', 'Miðvikudagar', 'Fimmtudagar', 'Föstudagar'];
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
		<?php
			for ($i = 0; $i < count($days); $i++) {
				echo '<h2>'.$days[$i].'</h2>';
				echo '<div class="flex-container">';

				$query = "SELECT buildings.name AS building_name,
								classrooms.name AS classroom_name,
								times.time_from AS time_from,
								times.time_to AS time_to
									FROM buildings
										INNER JOIN classrooms
											ON classrooms.building_id=buildings.id
										INNER JOIN times
											ON times.classroom_id=classrooms.id
												WHERE times.day_id=".($i + 1)
											." ORDER BY times.time_from ASC";
				$queryRes = $db->prepare($query);
				$queryRes->execute();

				$numRows = $queryRes->rowCount();

				if ($numRows === 0) {
					echo '<h3>Engin gögn fundust</h3>';
				} else {
					while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
						echo '<div class="flex-item">';
						echo '<h3>'.$row['classroom_name'].'</h3>';
						echo '<h4>'.$row['building_name'].'</h4>';
						echo '<p><b>Time:</b> '.$row['time_from'].' - '.$row['time_to'].'</p>';
						echo '</div>';
					}
				}
				
				$queryRes = null;

				echo '</div>'; /* .flex-container */
			}
		?>
		<br>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>
<?php $db = null; ?>