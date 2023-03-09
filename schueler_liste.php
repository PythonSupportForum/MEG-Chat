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
        <title>MEG Chat | Schueler</title>
        <meta name="description" content="<? echo htmlspecialchars($blog_data['text']); ?>">
        <meta name="keywords" lang="de" content="meg, max ernst gymnasium, schueler, suchen, liste, mitglieder">
        <meta name="author" content="Schüler des MEGs">
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
						    <button onclick="page_navigate('/schueler/<? echo htmlspecialchars($pupil_data['id']); ?>');" style="background-color: blue; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Einstellungen</button>
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
            <h1>Suchen Sie nach Schülern auf dem MEG-Chat</h1>
			<div style="width: 100%; height: 100px; " class="centriert"><input type="text" onchange="page_navigate(window.location.href.split('?')[0]+'?q='+this.value, '#searchresults_container');" placeholder="Suchen.." id="search" value="<? htmlspecialchars(isset($_GET['q']) ? $_GET['q'] : ""); ?>" style="width: 500px; max-width: 90%; height: 50px; font-size: 24px; "></div>
			<div style="width: 100%; " id="searchresults_container">
			    <?
			    $found = false;
				$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE activated = 1 AND LOWER(fullname) LIKE LOWER(:query) ORDER BY LOWER(fullname) ASC LIMIT 10000;");
				$stmtData->execute(array('query' => '%'.(isset($_GET['q']) ? $_GET['q'] : "").'%'));
				while($row = $stmtData->fetchObject()){ $row = (array)$row; $found = true; ?>
					<a href="javascript:page_navigate('/schueler/<? echo htmlspecialchars($row['id']); ?>');" style="color: black; text-decoration: none; "><div class="schueler_container">
					    <div style="height: calc( 100% - 60px ); width: 100%; margin-top: 10px; " class="centriert">
					        <img loading="lazy" style="width: 130px; height: 130px; border-radius: 50%; " src="<? echo htmlspecialchars(empty($row['avatar']) ? "/resources/images/avatar.png" : $row['avatar']); ?>">
					    </div>
					    <div style="width: 100%; height: 40px; word-wrap: break-word; " class="centriert">
					        <h3 style="word-wrap: break-word;"><? echo htmlspecialchars($row['fullname']); ?></h3>
					    </div>
					    <div style="width: 100%; height: 50px; font-size: 14px; overflow-x: hidden; overflow-y: scroll; " class="centriert no_scrollbar">
					        <div style="width: 100%; text-align: center; word-wrap: break-word; " class="centriert">
								<div style="text-align: center; width: auto; height: auto; ">
									<div style="margin-left: 8px; color: black; "><? echo htmlspecialchars($row['about_me']); ?></div>
								</div>
							</div>
					    </div>
					    <div style="width: 100%; height: 20px; font-size: 14px; " class="centriert">
		                    <div style="width: 100%; text-align: center; " class="centriert">
								<div style="text-align: center; width: auto; height: auto; ">
									<? if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $row['id'] != $pupil_data['id']){ ?><a style="color: black; " href="javascript:void(0); " onclick="event.stopPropagation(); vote('<? echo htmlspecialchars($row['id']); ?>');"><? } ?>
									<div class="schueler_vote_count_<? echo htmlspecialchars($row['id']); ?>" style="float: left; color: black; "><? echo $row['rating']; ?></div>
									<div style="float: left; margin-left: 8px; color: black; ">Stimmen</div>
									<? if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $row['id'] != $pupil_data['id']){ ?></a><? } ?>
								</div>
							</div>
					    </div>
					    <? if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $row['id'] != $pupil_data['id']){ ?>
						<div style="width: 100%; height: 25px; font-size: 14px; " class="centriert">
						    <button onclick="event.stopPropagation(); vote('<? echo htmlspecialchars($row['id']); ?>');">Gefällt Mir</button>
						</div>
						<? }?>
						<div style="width: 100%; height: 10px; "></div>
					</div></a>
				<? }
				if(!$found){
					?>
					<h2 style="text-align: center; margin-top: 50px; ">Es wurden keine Ergebnisse zu Deiner Suche gefunden.</h2>
					<?
				}
				?>
			</div>
        </div>
    </body>
</html>
