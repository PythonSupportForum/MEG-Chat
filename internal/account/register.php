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
	$email = $_POST['email'];
	
	if(strlen($name) < 4 || str_word_count($name) < 2){
		$error = "Bitte gebe Deinen vollständigen Namen an. Dazu zählt Vor- und Nachname. Ansonsten können wir Deinen Account nicht freischalten. ";
	}
	
	if(!$error){
		if(strlen($password) < 3){
			$error = "Das von Dir gewählte Passwort ist nicht sicher genug! Bitte verwende mindestens 3 Zeichen. ";
		}
	}
	
	if(!$error){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = "Die von dir angegebene Email Adresse ist ungültig! Bitte überprüfe die Rechtschreibung und versuche es erneut. ";
		}
	}
	
	if(!$error){
	    $stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE LOWER(fullname) = LOWER(:name) OR LOWER(email) = LOWER(:name);");
		$stmtCheck->execute(array('name' => $name));
		if($stmtCheck->rowCount() != 0){
			$error = "Es ist bereits ein Schüler mit diesem Namen regestriert! Bitte wählen Sie einen anderen Namen oder melden Sie diesen Schüler bei einem Administrator. Sie prüfen dann wem dieser Name in Wirklichkeit gehört und geben Ihnen dann eventuell den Zugriff!";
		}
	}
	
	if(!$error){
	    $stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE LOWER(email) = LOWER(:email) OR LOWER(fullname) = LOWER(:email);");
		$stmtCheck->execute(array('email' => $email));
		if($stmtCheck->rowCount() != 0){
			$error = "Es ist bereits ein Schüler mit dieser Email Adresse regestriert. Bitte melden Sie diesen Vorfall einem Adminstrator wenn Sie denken, dass diese Person nicht unter dieser Email Adresse regestriert sein darf.";
		}
	}
	
	if(!$error){
		function hashPassword($userPassword){
			return hash("sha512", $userPassword);
		}
		function generateRandomString($length = 16) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		
		$passwordSalt = generateRandomString(32);
	    $passwordHash = hashPassword($passwordSalt.$password);
	            
		$stmtAdd = $db->prepare("INSERT INTO ".DBTBL.".pupils (fullname, password_hash, password_salt, email) VALUES (:name, :password_hash, :password_salt, :email);");
		if($stmtAdd->execute(array('name' => $name, 'password_hash' => $passwordHash, 'password_salt' => $passwordSalt, 'email' => $email))){
			$success = "Dein Account wurde erfolgreich eingetragen und wartet Jetzt auf die Freischaltung durch einen Administrator. Das dauert in der Regel maximal einen Tag.";
			$_SESSION['pupil'] = $db->lastInsertId();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEG Chat | Eintragen</title>
        <meta name="description" content="Regestriere dich als Schüler um an allen Aktivitäten teilnehmen zu können. Nach dem Erstellen deines Kontos, prüfen wir zunächst deine Identität. Anschließend kanst du Gruppen und Kurse beitreten und nich an Chats beteiligen. ">
        <meta name="keywords" lang="de" content="meg, max, ernst, gymnasium, chat, online, schueler, chatten, austauschen, hausaufgaben, fragen">
        <? require('../middleware/head.php'); ?>
    </head>
    <body>
    <div class="login-wrapper">
        <div class="login-container">
			<form action="/account/register" method="POST">
                <h2>Mich als Schüler anmelden</h2>
			  <label for="name">Vor und Nachname:</label><br>
			  <input type="text" id="name" name="name" placeholder="Max Mustermann" autocomplete="on"><br>
			  <label for="email" id="email">E-mail:</label><br>
			  <input type="email" id="email" name="email" placeholder="muster.max@meg-bruehl.de" autocomplete="on"><br>
			  <label for="password">Passwort:</label><br>
			  <input type="password" id="password" name="password"  autocomplete="on"><br><br>
                Ihr Account muss anschließend erst von einem Administrator aktiviert werden.
			  <input id="submit" name="submit" type="submit" value="Eintragen">
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
            width: 17%;
            display: flex;
            flex-direction: row;
            border-radius: 15px;
        }
        #submit {
            border-radius: 15px;
            border: none;
            width: 30%;
            height: 10%;
            padding: 2%;
        }
        #name {
            border-radius: 15px;
            padding: 2px;
            margin-bottom: 2px;
        }
        #password {
            border-radius: 15px;
            padding: 2px;
        }
        #email {
            border-radius: 15px;
            margin-bottom: 2px;
        }
    </style>
</html>

