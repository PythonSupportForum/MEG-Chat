<?php
require_once("../logic/db.php");

$error = false;
if(isset($_SESSION['pupil'])){
	header("Location: /");
    return exit();
}

if(isset($_POST['submit'])){
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
		$_SESSION['pupil'] = $check['id'];
		header("Location: /");
        return exit();
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
        <?php require('../middleware/head.php'); ?>
    </head>
    <body>
    <div class="login-wrapper">
        <div class="login-container">
                <form class="bottom_login" action="/account/login" method="POST">
                    <h2>Anmelden</h2>
                    <?php
	              if($error) {
					  ?>
					  <p style="color: red; text-align: center; font-size: 16px; "><?php echo htmlspecialchars($error); ?></p>
                      <?php
			      }
				  ?>
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
            min-width: 20%;
            width: 300px;
            max-width: 100%;
            display: flex;
            flex-direction: row;
            border-radius: 15px;
            height: auto;
            min-height: 100px;
            padding: 20px;
        }
        #submit {
            border-radius: 15px;
            border: none;
            width: 30%;
            height: 40px;
            padding: 2%;
            margin-top: 15px;
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
