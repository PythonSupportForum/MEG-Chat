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
	        <div id="all_chats_container">
				<div style="margin-top: 60px; width: 100%; height: auto; " class="public_chats_container" id="public_chats_container">
					<h2>Öffentliche Chattgruppen:</h2>
					<?
					$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE public = 1; ");
					$stmtData->execute(array('id' => $_SESSION['pupil']));
					while($row = $stmtData->fetchObject()){
						$row = (array)$row;
						$count = 0;
						if(isset($_SESSION['pupil'])){
							$stmtChat = $db->prepare("SELECT * FROM ".DBTBL.".chats_members WHERE pupil = :pupil AND chat = :chat;");
						    $stmtChat->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $row['id']));
						    $last_readed_message = -1;
						    if($member = $stmtChat->fetchObject()){
								$member = (array)$member;
								$last_readed_message = $member['last_readed_message'];
							}
							$stmtCount = $db->prepare("SELECT COUNT(id) as count FROM ".DBTBL.".chats_messages WHERE chat = :chat AND id > :last AND time > :time;");
						    $stmtCount->execute(array('chat' => $row['id'], 'last' => $last_readed_message, 'time' => $pupil_data['registartion_time']));
						    $count = ((array)$stmtCount->fetchObject())['count'];
						}
						?>
						<div class="chatgruppe" onclick="page_navigate('/chat/<? echo htmlspecialchars($row['id']); ?>');">
						    <div style="width: 100%; min-height: 40px; height: auto; ">
							    <div style="height: auto; width: 100%; min-height: 40px; ">
									<div style="width: calc( 100% - 120px ); ">
							            <h4 style="margin: 0; padding: 0; font-size: 18px; "><? echo htmlspecialchars($row['name']); ?></h4>
							            <h6 style="margin: 0; padding: 0; font-size: 14px; font-weight: small; "><? echo htmlspecialchars($row['description']); ?></h6>
							        </div>
							        <? if($count > 0){ ?>
								    <div style="position: absolute; float: right; min-height: 40px; height: auto; width: 100px; " class="centriert">
								        <div style="height: 90%; width: 80%; background-color: red; color: white; border-radius: 10px; font-size: 24px; " class="centriert"><? echo htmlspecialchars($count); ?></div>
								    </div>
								    <? } ?>
							    </div>
						    </div>
						</div>
		            <? } ?>
				</div>
			</div>
			<div style="margin-top: 40px; width: 100%; height: auto; min-height: 320px; ">
				<h2>Beliebstete Schüler:</h2>
				<?
				$stmtData = $db->prepare("SELECT ".DBTBL.".pupils.*, COUNT(".DBTBL.".pupils_votes.s_to) AS rating_count, COALESCE(SUM(points),0) as rating FROM ".DBTBL.".pupils LEFT JOIN ".DBTBL.".pupils_votes ON ".DBTBL.".pupils.id = ".DBTBL.".pupils_votes.s_to WHERE activated = 1 GROUP BY ".DBTBL.".pupils.id ORDER BY rating DESC;");
				$stmtData->execute(array('id' => $_SESSION['pupil']));
				while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
					<a href="javascript:page_navigate('/schueler/<? echo htmlspecialchars($row['id']); ?>');" style="color: black; text-decoration: none; "><div class="schueler_container">
					    <div style="height: calc( 100% - 60px ); width: 100%; margin-top: 10px; " class="centriert">
					        <img style="width: 130px; height: 130px; border-radius: 50%; " src="<? echo htmlspecialchars(empty($row['avatar']) ? "/resources/images/avatar.png" : $row['avatar']); ?>">
					    </div>
					    <div style="width: 100%; height: 40px; word-wrap: break-word; " class="centriert">
					        <h3 style="word-wrap: break-word;"><? echo htmlspecialchars($row['fullname']); ?></h3>
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
				<? } ?>
			</div>
			<div style="margin-top: 40px; width: 100%; height: auto; ">
				<h2>Neuigkeiten aus unserem Blog:</h2>
				<?
				$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".blog ORDER BY time DESC;");
				$stmtData->execute(array('id' => $_SESSION['pupil']));
				while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
					<a href="javascript:page_navigate('/blog/<? echo htmlspecialchars($row['id']); ?>');" style="color: black; "><div class="blog_entry">
					    <div style="width: 100%; height: 100px; margin-top: 20px; " class="centriert">
					        <img style="height: 100%; width: auto; " src="<? echo htmlspecialchars($row['image']); ?>">
					    </div>
					    <div style="width: 100%; margin-top: 20px; " class="centriert">
					        <h2><? echo htmlspecialchars($row['header']); ?></h2>
					    </div>
					    <div style="width: calc( 100% - 10px ); margin-top: 20px; margin-left: 5px; ">
					        <p style="font-size: 16px; "><? echo htmlspecialchars($row['text']); ?></p>
					    </div>
					</div></a>
				<? } ?>
			</div>
		</div>
    </body>
</html>
