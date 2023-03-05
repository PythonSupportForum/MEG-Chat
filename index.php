<?
require_once("db.php");

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
        <link rel="stylesheet" href="/resources/css/style.css">
    </head>
    <body>
		<div class="head centriert">
			<div style="text-align: center; ">
				<div style="height: 100%; float: left; min-width: 160px; width: auto; " class="centriert">
				    <img style="height: 150px; text-align: center; " src="/logo.png">
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
							<?
							if(isset($_SESSION['pupil'])){
								?>
								<h2 style="margin-top: 5px; ">Du bist angemeldet als <? echo htmlspecialchars($pupil_data['fullname']); ?>!</h2>
								<?
								if($pupil_data['activated'] == 0){
									?>
									<p style="color: red; font-size: 10px; ">Dein Account ist noch nicht freigeschaltet worden. Bitte gedulte dich einige Zeit oder Kontaktiere einen Administrator. Wir werden deine Identität Prüfen und den Account anschließend freischalten.</p>
									<?
							    }
								?>
								<button onclick="page_navigate('/settings.php');" style="background-color: blue; color: white; font-size: 16px; ">Einstellungen</button>
								<button onclick="window.location.href='/logout.php';" style="background-color: red; color: white; font-size: 16px; ">Abmelden</button>
								<?
			                } else { ?>
			                    <button onclick="page_navigate('/login.php');">Anmelden</button>
			                    <button onclick="page_navigate('/register.php');">Mich als Schüler hinzufügen</button>
			                <? } ?>
		                </div>
		            </div>
		        </div>
	        </div>
        </div>
        <div>
			<div style="width: 100%; margin-top: 20px; ">
		        <div id="all_container">
					<? require("public_chats.php"); ?>
				</div>
				<? require("beliebteste_schueler.php"); ?>
				<? require("blog_news.php"); ?>
			</div>
		</div>
    </body>
</html>
