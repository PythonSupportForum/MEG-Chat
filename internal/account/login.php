<?
require_once("../logic/db.php");

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
        <? require('../middleware/head.php'); ?>
    </head>
    <body>
    <div class="login-wrapper">
        <div class="login-container">
                <form class="bottom_login" action="/account/login" method="POST">
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
                        <a href="/account/register">Register</a>
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
</html>
