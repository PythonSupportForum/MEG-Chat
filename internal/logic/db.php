<?php
    ob_start();
    session_start();
	error_reporting();

	date_default_timezone_set('Europe/Berlin');
	
	define('DBHOST','127.0.0.1');
	define('DBUSER','root');
	define('DBPASS','Ttito1607200707');
	define('DBTBL', 'meg');

	try {
		$db = new PDO("mysql:host=".DBHOST.";charset=utf8mb4;", DBUSER, DBPASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
	    echo $e->getMessage();
	    exit;
	}
?>
