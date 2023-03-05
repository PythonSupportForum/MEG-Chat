<?
require_once("db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}

$s = $_GET['schueler'];

$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
$stmtData->execute(array('id' => $s));
$row = $stmtData->fetchObject();
$s_data = (array)$row;
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEG Chat | Schüler | <? echo htmlspecialchars($s['fullname']); ?></title>
        <meta name="description" content="<? echo htmlspecialchars($s['about_me']); ?>">
        <meta name="keywords" lang="de" content="max ernst gymnasium, meg, schüler, schueler, klasse, informtionen, profil, <? echo htmlspecialchars($s['fullname']); ?>">
        <meta name="author" content="<? echo htmlspecialchars($s['fullname']); ?>">
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
        <div style="float: left; width: 160px; max-width: 100%; ">
            <div style="width: 100%; height: auto;" class="centriert"><img style="width: 100%;" src="/logo.png" alt="MEG Chat Logo"></div>
            <div style="width: 100%; height: auto; " class="centriert"><h2>MEG Chat</h2></div>
            <div style="width: 100%; height: auto; border-top: 1px solid black; " class="centriert">
                <h2 style="font-size: 16px; " class="text"><a href="javascript:page_navigate('/');" class="text">Zur Startseite</a></h2>
            </div>
            <div style="width: 100%; height: auto; border-top: 1px solid black; margin-top: 10px; text-align: center; overflow: hidden; " class="centriert">
				<div style="width: 100%; text-align: center; height: auto; margin-top: 10px; ">
	                <?
					if(isset($_SESSION['pupil'])){
						?>
						<h2 style="margin-top: 5px; font-size: 14px; word-wrap: break-word; ">Du bist angemeldet als <? echo htmlspecialchars($pupil_data['fullname']); ?>!</h2>
						<?
						if($pupil_data['activated'] == 0){
							?>
							<p style="color: red; font-size: 10px; ">Dein Account ist noch nicht freigeschaltet worden. Bitte gedulte dich einige Zeit oder Kontaktiere einen Administrator. Wir werden deine Identität Prüfen und den Account anschließend freischalten.</p>
							<?
					    }
						?>
						<div style="width: 100%; height: auto; margin-top: 10px; ">
						    <button onclick="page_navigate('/settings.php');" style="background-color: blue; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Einstellungen</button>
						    <button onclick="window.location.href='/logout.php';" style="background-color: red; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Abmelden</button>
						</div>
						<?
	                } else { ?>
						<div style="width: 100%; height: auto; margin-top: 10px; ">
	                        <button onclick="page_navigate('/login.php');" style="width: 100%; height: 25px; margin-top: 10px; ">Anmelden</button>
	                        <button onclick="page_navigate('/register.php');" style="width: 100%; height: 50px; margin-top: 10px; ">Mich als Schüler hinzufügen</button>
	                    </div>
	                <? } ?>
                </div>
            </div>
        </div>
        <div style="float: left; width: calc( 100% - 162px ); min-width: 600px; max-width: 100%; text-align: center;">
			<? if(!$s_data){
			    ?>
			    <h1>Das Profil dieses Schülers konnte nicht gefunden werden!</h1>
			    <?	
			} else { ?>
                <h1><? echo htmlspecialchars($s_data['fullname']); ?></h1>
                <div style="width: 100%; height: auto; " class="centriert">
                    <img style="width: 300px; max-height: 300px; height: auto; max-width: 100%; border-radius: 50%; " src="<? echo htmlspecialchars(empty($s_data['avatar']) ? "/resources/images/avatar.png" : $s_data['avatar']); ?>">
                </div>
                <div style="width: 100%; height: auto; margin-top: 10px; " class="centriert">
                    <div style="width: 500px; max-width: 100%; font-size: 16px;"><? echo htmlspecialchars($s_data['about_me']); ?></div>
                </div>
                <div style="width: 100%; height: auto; margin-top: 10px; ">
					<div class="tab">
					  <button class="tablinks" onclick="openTab(event, 'profile')">Profil</button>
					  <button class="tablinks" onclick="openTab(event, 'chats_together')">Gemeinsame Chats</button>
					  <button class="tablinks" onclick="openTab(event, 'contact')">Kontakt</button>
					</div>
				</div>
                <div id="profile" class="tabcontent">
				  <h3>London</h3>
				  <p>London is the capital city of England.</p>
				</div>
				<div id="chats_together" class="tabcontent">
				  <h3>Paris</h3>
				  <p>Paris is the capital of France.</p> 
				</div>
				
				<div id="contact" class="tabcontent">
				  <h3>Tokyo</h3>
				  <p>Tokyo is the capital of Japan.</p>
				</div>
            <? } ?>
        </div>
    </body>
</html> 
