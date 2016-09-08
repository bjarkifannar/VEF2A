<?php
	$dbuser = '2809983979';
	$dbpass = 'bZvZzW7YfpLrfzhy';
	$dbhost = 'localhost';
	$dbname = '2809983979_vef2a_classrooms';

	try {
		$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		echo "Connection failed! ".$e->getMessage();
	}
?>