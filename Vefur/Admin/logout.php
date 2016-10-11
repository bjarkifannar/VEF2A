<?php
	require_once 'core/init.php';

	$_SESSION = array();
	$params = session_get_cookie_params();
	setcookie(session_name(),
		'', time() - 42000,
		$params["path"],
		$params["domain"],
		$params["secure"],
		$params["httponly"]);
	
	session_destroy();

	header('Location: login.php');
?>