<?php
	$pageName = 'Admin - Execute File';

	$filePath = 'Uploads/';
	$filename = isset($_GET['file']) ? $_GET['file'] : null;
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
				if ($filename !== null) {
					$readDataClass = new ReadClassroomData($filePath.$filename, $db);

					$readDataRes = $readDataClass->GetMessages();

					if (!empty($readDataRes)) {
						foreach ($readDataRes as $msg) {
							echo '<h3 align="center">'.$msg.'</h3>';
						}
					} else {
						header('Location: index.php');
					}
				}
			}
		?>
		<?php require_once 'inc/footer.php'; ?>
	</body>
</html>