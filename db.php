<?
    ob_start();
    session_start();
	error_reporting();

	date_default_timezone_set('Europe/Berlin');
	
	define('DBHOST','167.235.78.39');
	define('DBUSER','infinity1_app');
	define('DBPASS','GP74INXYJo-E#q6/');
	define('DBTBL', 'infinity1_meg');

	try {
		$db = new PDO("mysql:host=".DBHOST.";charset=utf8mb4;", DBUSER, DBPASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
	    echo $e->getMessage();
	    exit;
	}
?>
