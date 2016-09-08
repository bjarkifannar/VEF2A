<?php
	$pageName = 'Forsíða';
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
		<?php
			/*$json_content = file_get_contents('data.json');
			$json = json_decode($json_content, true);
			
			//print_r($json);

			$buildingArray = $json["buildings"];

			print_r($buildingArray);*/

			$query = "SELECT id, name FROM buildings";
			$queryRes = $db->prepare($query);
			$queryRes->execute();

			while ($row = $queryRes->fetch(PDO::FETCH_ASSOC)) {
				echo $row['id'].': '.$row['name'].'<br>';
			}

			$queryRes = null;
		?>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>
<?php $db = null; ?>