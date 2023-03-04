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
        <meta name="author" content="Lars Ashauer und Tilo Behnke">
        <meta name="robots" content="index,follow">
        <meta http-equiv="Cache-control" content="public">
        <meta name="format-detection" content="telephone=yes">
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <script src="/resources/js/script.js"></script>
    </head>
    <body>
        <div class ="all">
		<h2 id = "login">Anmelden</h2>
            
        <?
        if($total_error){
			?>
			<h2 style="color: red; "><? echo htmlspecialchars($total_error); ?></h2>
			<h2><a href="/">Zur Startseite</a></h2>
			<?
		} else {
	        if($error){
			    ?>
			    <p style="color: red; font-size: 18px; "><? echo htmlspecialchars($error); ?></p>
			    <h2><a href="/">Zur Startseite</a></h2>
			    <?	
			}
	        ?>
	        <?
	        if($success){
			    ?>
			    <p style="color: green; font-size: 18px; "><? echo htmlspecialchars($success); ?></p>
			    <h2><a href="/">Zur Startseite</a></h2>
			    <?	
			} else {
	        ?>
			<form class = "bottom_login" action="/login.php" method="POST">
			  <label for="name">Name oder Email Adresse:</label><br>
			  <input class = "input" type="text" id="name" name="name" placeholder="Benutzername" autocomplete="on"><br>
			  <label for="password">Passwort:</label><br>
			  <input class = "input" type="password" id="password" name="password" placeholder="Passwort" autocomplete="on"><br><br>
			  <input id="submit" name="submit" type="submit" value="Anmelden">
			</form> 
		<? } } ?>
        </div>
    </body>
    <style>
        #submit{
            font-size: 300%;
        }
        .input{
            height: 150%;
            width: 180;
        }
        .bottom_login{
            margin: 10px;
            font-size: 200%;
        }
        .all {
            text-align: center;
        }
        #login {
            font-size:400%;
        }
        html, body {
			background-color: #303030;
			color: #e0e0e0;
		}
		h1, h2, p, a, .text {
			color: #e0e0e0;
            
		}
    </style>
</html>
