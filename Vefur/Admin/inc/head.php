<?php
	require_once 'core/init.php';

	$logged = 'out';

	if ($login->login_check()) {
		$logged = 'in';
	}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $pageName; ?></title>
<link rel="stylesheet" type="text/css" href="../css/style.css">