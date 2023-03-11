<?php
require_once("../logic/db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}

$blog = $_GET['blog'];

$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".blog WHERE id = :id;");
$stmtData->execute(array('id' => $blog));
$row = $stmtData->fetchObject();
$blog_data = (array)$row;

if(isset($_SERVER['HTTP_USER_AGENT'])){
    $is_mobile = preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|boost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
} else {
	$is_mobile = isset($_COOKIE['desktop']) ? ($_COOKIE['desktop'] == "a") : true;
}
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEG Chat | Blog | <?php echo htmlspecialchars($blog_data['header']); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars($blog_data['text']); ?>">
        <meta name="keywords" lang="de" content="meg, max, ernst, gymnasium, chat, online, schueler, chatten, austauschen, hausaufgaben, fragen, blog, artikel, austausch, kontakt">
        <? require('../middleware/head.php'); ?>
    </head>
    <body>
		<?php if(!$is_mobile) { ?>
        <div style="float: left; width: 160px; max-width: 100%; ">
            <div style="width: 100%; height: auto;" class="centriert"><img style="width: 100%;" src="/resources/images/logo.png" alt="MEG Chat Logo"></div>
            <div style="width: 100%; height: auto; " class="centriert"><h2>MEG Chat</h2></div>
            <div style="width: 100%; height: auto; border-top: 1px solid black; " class="centriert">
                <h2 style="font-size: 16px; " class="text"><a href="javascript:page_navigate('/');" class="text">Zur Startseite</a></h2>
            </div>
            <div style="width: 100%; height: auto; border-top: 1px solid black; margin-top: 10px; text-align: center; overflow: hidden; " class="centriert">
				<div style="width: 100%; text-align: center; height: auto; margin-top: 10px; ">
                    <?php
					if(isset($_SESSION['pupil'])){
						?>
						<h2 style="margin-top: 5px; font-size: 14px; word-wrap: break-word; ">Du bist angemeldet als <?php echo htmlspecialchars($pupil_data['fullname']); ?>!</h2>
                        <?php
						if($pupil_data['activated'] == 0){
							?>
							<p style="color: red; font-size: 10px; ">Dein Account ist noch nicht freigeschaltet worden. Bitte gedulte dich einige Zeit oder Kontaktiere einen Administrator. Wir werden deine Identität Prüfen und den Account anschließend freischalten.</p>
                            <?php
					    }
						?>
						<div style="width: 100%; height: auto; margin-top: 10px; ">
						    <button onclick="page_navigate('/schueler/<?php echo htmlspecialchars($pupil_data['id']); ?>');" style="background-color: blue; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Einstellungen</button>
						    <button onclick="window.location.href='/account/logout';" style="background-color: red; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Abmelden</button>
						</div>
                        <?php
	                } else { ?>
						<div style="width: 100%; height: auto; margin-top: 10px; ">
	                        <button onclick="page_navigate('/account/login');" style="width: 100%; height: 25px; margin-top: 10px; ">Anmelden</button>
	                        <button onclick="page_navigate('/account/register');" style="width: 100%; height: 50px; margin-top: 10px; ">Mich als Schüler hinzufügen</button>
	                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <div style="float: left; width <?php if(!$is_mobile){ ?>calc( 100% - 162px )<?php } else { ?>100%<?php } ?>; min-width: 300px; max-width: 100%; text-align: center;">
            <?php if(!$blog_data){
			    ?>
			    <h1>Dieser Blogbeitrag konnte nicht gefunden werden!</h1>
                <?php
			} else { ?>
                <h1><?php echo htmlspecialchars($blog_data['header']); ?></h1>
                <div style="width: 100%; height: auto; " class="centriert">
                    <img style="width: 500px; max-width: 100%; " src="<?php echo htmlspecialchars($blog_data['image']); ?>">
                </div>
                <div style="width: 100%; height: auto; margin-top: 10px; " class="centriert">
                    <div style="width: 500px; max-width: 100%; font-size: 16px;"><?php echo htmlspecialchars($blog_data['text']); ?></div>
                </div>
            <?php } ?>
        </div>
    </body>
</html>
