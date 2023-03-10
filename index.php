<?php
require_once("internal/logic/db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEG Chat | Startseite</title>
        <meta name="description" content="Der Max Ernst Gymnasium Schüler Chat! Hier tauschen sich Schüler über Ihre Schule und alles was damit zusammenhängt aus. WhatsApp is out!">
        <meta name="keywords" lang="de" content="meg, max, ernst, gymnasium, chat, online, schueler, chatten, austauschen, hausaufgaben, fragen">
        <? require('internal/middleware/head.php'); ?>
    </head>
    <body>
		<div class="head centriert">
			<div style="text-align: center; ">
				<div style="height: 100%; float: left; min-width: 160px; width: auto; " class="centriert">
				    <img style="height: 150px; text-align: center; " src="/resources/images/logo.png">
				</div>
				<div style="height: 100%; min-height: 150px; margin-left: 20px; float: left; min-width: 200px; width: auto; " class="centriert">
					<div style="text-align: center; ">
		                <h1 style="font-size: 28px; margin: 0; padding: 0; ">Der MEG Chat!</h1>
		                <h2 style="font-size: 20px; margin: 0; padding: 0; margin-top: 10px; ">Schüler unter Schülern</h2>
		            </div>
		        </div>
		        <div style="height: 100%; min-height: 150px; margin-left: 20px; float: left; min-width: 200px; width: auto; max-width: calc( 100% - 40px ); " class="centriert">
					<div style="max-width: 400px; width: auto; text-align: center; border: 1px solid black; border-radius: 10px; padding: 10px; margin-top: 10px; ">
						<div>
                            <?php
							if(isset($_SESSION['pupil'])){
								?>
								<h2 style="margin-top: 5px; ">Du bist angemeldet als <?php echo htmlspecialchars($pupil_data['fullname']); ?>!</h2>
                                <?php
								if($pupil_data['activated'] == 0){
									?>
									<p style="color: red; font-size: 10px; ">Dein Account ist noch nicht freigeschaltet worden. Bitte gedulde dich einige Zeit oder Kontaktiere einen Administrator. Wir werden deine Identität Prüfen und den Account anschließend freischalten.</p>
                                    <?php
							    }
								?>
								<button onclick="page_navigate('/schueler/<?php echo htmlspecialchars($pupil_data['id']); ?>');" style="background-color: blue; color: white; font-size: 16px; ">Einstellungen</button>
								<button onclick="window.location.href='/account/logout';" style="background-color: red; color: white; font-size: 16px; ">Abmelden</button>
                                <?php
			                } else { ?>
			                    <button onclick="page_navigate('/account/login');">Anmelden</button>
			                    <button onclick="page_navigate('/account/register');">Mich als Schüler hinzufügen</button>
                            <?php } ?>
		                </div>
		            </div>
		        </div>
	        </div>
        </div>
        <div>
			<div style="width: 100%; margin-top: 20px; ">
		        <div id="all_container">
                    <?php require("internal/information/public_chats.php"); ?>
				</div>
                <?php require("internal/information/beliebteste_schueler.php"); ?>
                <?php require("internal/information/blog_news.php"); ?>
                <?php require("internal/information/projects.php"); ?>
			</div>
		</div>
    </body>
</html>
