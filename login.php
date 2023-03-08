<?
require_once("db.php");

$error = false;
$success = false;
$total_error = false;
if(isset($_SESSION['pupil'])){
	$total_error = "Du bist bereits Angemeldet. ";
	
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$check = (array)$stmtCheck->fetchObject();
	
	if($check['activated'] == 0){
		$total_error .= "Bitte habe einen Moment Gedult bis Dein Account freigeschaltet wird. Das kann einige Zeit dauern bis wir Deine Identität überprüft haben. Bitte kontaktiere einen Administrator um den Vorgang zu beschleunigen.";
	}
}

if(isset($_POST['submit']) && !$total_error){
	$name = trim($_POST['name']);
	$password = $_POST['password'];
	

    $stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE LOWER(fullname) = LOWER(:name) OR LOWER(email) = LOWER(:name);");
	$stmtCheck->execute(array('name' => $name));
	if($stmtCheck->rowCount() == 0){
		$error = "Dieser Account exestiert nicht! Wenn du noch keinen Account hast, kanst du einen erstellen.";
	} else {
	    $check = (array)$stmtCheck->fetchObject();	
	}
	
	if(!$error){
		function hashPassword($userPassword){
			return hash("sha512", $userPassword);
		}
		
		$passwordSalt = $check['password_salt'];
	    $passwordHash = hashPassword($passwordSalt.$password);
	            
		if($passwordHash != $check['password_hash']){
			$error = "Das eingegebene Passwort ist falsch! Bitte probiere es erneut.";
		}
	}
	
	if(!$error){
		$success = "Du hast dich erfolgreich angemeldet. Du bist jetzt als ".htmlspecialchars($check['fullname'])." angemeldet.";
		$_SESSION['pupil'] = $check['id'];
    }
}
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEG Chat | Anmelden</title>
        <meta name="description" content="Melde Dich mit deinem MEG Chat Konto an. ">
        <meta name="keywords" lang="de" content="meg, max, ernst, gymnasium, chat, online, schueler, chatten, austauschen, hausaufgaben, fragen, anmeldung">
        <meta name="author" content="Lars Ashauer, Tilo Behnke und Frittenfresse">
        <meta name="robots" content="index,follow">
        <meta http-equiv="Cache-control" content="public">
        <meta name="format-detection" content="telephone=yes">
        <link rel="apple-touch-icon" sizes="57x57" href="/icons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/icons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/icons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/icons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/icons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/icons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <script src="/resources/js/script.js"></script>
        <link rel="stylesheet" href="/resources/css/style.css">
    </head>
    <body>
    <div class="login-wrapper">
        <div class="login-container">
                <form class = "bottom_login" action="/login.php" method="POST">
                    <h2>Anmelden</h2>
                 <div>
                      <label for="name">Name oder Email Adresse:</label>
                      <input class = "input" type="text" id="name" name="name" placeholder="Benutzername" autocomplete="on">
                 </div>
                  <div class="password">
                      <label for="password">Passwort:</label><br>
                      <input class = "input" type="password" id="password" name="password" placeholder="Passwort">
                  </div>
                   <div class="login-register">
                        <a href="/register.php">Register</a>
                        <a href="/">Passwort vergessen</a>
                   </div>
                  <input id="submit" name="submit" type="submit" value="Anmelden">
                </form>
            </div>
        </div>
    </body>
    <style>
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 50%;
        }
        .login-container {
            background:#525252;
            width: 20%;
            display: flex;
            flex-direction: row;
            border-radius: 15px;
        }
        #submit {
            border-radius: 15px;
            border: none;
            width: 30%;
            height: 15%;
            padding: 2%;
        }
        #name {
            border-radius: 15px;
            padding: 2px;
            margin-bottom: 5%;
        }
        #password {
            border-radius: 15px;
            padding: 2px;
        }
        .password {
            padding-bottom: 1%;
        }
        .login-register {
            padding: 1%;
        }
    </style>
    <style><? require("resources/css/style.css"); ?></style>
</html>
