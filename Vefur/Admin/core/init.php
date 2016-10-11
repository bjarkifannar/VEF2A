<?php
	require_once '../inc/db_connect.php';
	require_once dirname(__FILE__).'/classes.php';
	
	$login = new LoginClass();
	$login->sec_session_start();
?>