<?php
	$pageName = 'Admin - Upload File';

	/* Maximum upload size in bytes */
	$max = 512 * 1024; /* 512 KB */
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
				if (isset($_POST['upload'])) {
					/* Define the path to the upload folder */
					$destination = __DIR__.'\\Uploads\\';

					try {
						$loader = new FileUpload($destination, $db);
						$loader->SetMaxSize($max);
						$tmpFile = $loader->Upload();
						$result = $loader->GetMessages();
						echo $tmpFile;
					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}

				if (isset($result)) {
					echo '<ul>';
					
					foreach ($result as $message) {
						echo "<li>$message</li>";
					}

					echo '</ul>';
				}
		?>
		<form action="" method="POST" enctype="multipart/form-data" id="upload-file">
			<label for="file-input">File to upload: </label>
			<input type="file" name="file" id="file-input">
			<input type="submit" name="upload" id="upload" value="Upload">
		</form>
		<h3>Already uploaded files:</h3>
		<?php
				$fileQuery = "SELECT file_name FROM files";
				$fileRes = $db->prepare($fileQuery);
				$fileRes->execute();

				while ($row = $fileRes->fetch(PDO::FETCH_ASSOC)) {
					echo '<a href="execute_file.php?file='.$row['file_name'].'">'.$row['file_name'].'</a><br>';
				}

				$fileQuery = $fileRes = null;
			}
		?>
		<?php require_once 'inc/footer.php'; ?>
		<script type="text/javascript" src="../js/sha512.js"></script>
		<script type="text/javascript" src="../js/forms.js"></script>
	</body>
</html>