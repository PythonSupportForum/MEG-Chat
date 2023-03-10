<?php
require_once("../logic/db.php");

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
        <meta name="description" content="<?php echo htmlspecialchars($blog_data['text']); ?>">
        <meta name="keywords" lang="de" content="meg, max ernst gymnasium, schueler, suchen, liste, mitglieder">
        <?php require('../middleware/head.php'); ?>
    </head>
    <body>
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
						    <a href="/schueler/<?php echo htmlspecialchars($pupil_data['id']); ?>"><button" style="background-color: blue; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Einstellungen</button></a>
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
        <div style="float: left; width: calc( 100% - 162px ); min-width: 600px; max-width: 100%; text-align: center;">
            <h1>Suchen Sie nach Schülern auf dem MEG-Chat</h1>
			<div style="width: 100%; height: 100px; " class="centriert"><input type="text" onchange="page_navigate(window.location.href.split('?')[0]+'?q='+this.value, '#searchresults_container');" placeholder="Suchen.." id="search" value="<?php htmlspecialchars(isset($_GET['q']) ? $_GET['q'] : ""); ?>" style="width: 500px; max-width: 90%; height: 50px; font-size: 24px; "></div>
			<div style="width: 100%; " id="searchresults_container">
                <?php
			    $found = false;
				$stmtData = $db->prepare("SELECT ".DBTBL.".pupils.*, COUNT(".DBTBL.".pupils_votes.s_to) AS rating_count, COALESCE(SUM(points),0) as rating FROM ".DBTBL.".pupils LEFT JOIN ".DBTBL.".pupils_votes ON ".DBTBL.".pupils.id = ".DBTBL.".pupils_votes.s_to WHERE activated = 1 AND LOWER(fullname) LIKE LOWER(:query) GROUP BY ".DBTBL.".pupils.id ORDER BY LOWER(fullname) ASC LIMIT 10000;");
				$stmtData->execute(array('query' => '%'.(isset($_GET['q']) ? $_GET['q'] : "").'%'));
				while($row = $stmtData->fetchObject()){ $row = (array)$row; $found = true; ?>
					<a href="javascript:page_navigate('/schueler/<?php echo htmlspecialchars($row['id']); ?>');" style="color: black; text-decoration: none; "><div class="schueler_container">
					    <div style="height: calc( 100% - 60px ); width: 100%; margin-top: 10px; " class="centriert">
					        <img loading="lazy" style="width: 130px; height: 130px; border-radius: 50%; " src="<?php echo htmlspecialchars(empty($row['avatar']) ? "/resources/images/avatar.png" : $row['avatar']); ?>">
					    </div>
					    <div style="width: 100%; height: 40px; word-wrap: break-word; " class="centriert">
					        <h3 style="word-wrap: break-word;"><?php echo htmlspecialchars($row['fullname']); ?></h3>
					    </div>
					    <div style="width: 100%; height: 50px; font-size: 14px; overflow-x: hidden; overflow-y: scroll; " class="centriert no_scrollbar">
					        <div style="width: 100%; text-align: center; word-wrap: break-word; " class="centriert">
								<div style="text-align: center; width: auto; height: auto; ">
									<div style="margin-left: 8px; color: black; "><?php echo htmlspecialchars($row['about_me']); ?></div>
								</div>
							</div>
					    </div>
					    <div style="width: 100%; height: 20px; font-size: 14px; " class="centriert">
		                    <div style="width: 100%; text-align: center; " class="centriert">
								<div style="text-align: center; width: auto; height: auto; ">
                                    <?php if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $row['id'] != $pupil_data['id']){ ?><a style="color: black; " href="javascript:void(0); " onclick="event.stopPropagation(); vote('<?php echo htmlspecialchars($row['id']); ?>');"><?php } ?>
									<div class="schueler_vote_count_<?php echo htmlspecialchars($row['id']); ?>" style="float: left; color: black; "><?php echo $row['rating']; ?></div>
									<div style="float: left; margin-left: 8px; color: black; ">Stimmen</div>
                                        <?php if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $row['id'] != $pupil_data['id']){ ?></a><?php } ?>
								</div>
							</div>
					    </div>
                            <?php if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $row['id'] != $pupil_data['id']){ ?>
						<div style="width: 100%; height: 25px; font-size: 14px; " class="centriert">
						    <button onclick="event.stopPropagation(); vote('<?php echo htmlspecialchars($row['id']); ?>');">Gefällt Mir</button>
						</div>
                            <?php }?>
						<div style="width: 100%; height: 10px; "></div>
					</div></a>
                <?php }
				if(!$found){
					?>
					<h2 style="text-align: center; margin-top: 50px; ">Es wurden keine Ergebnisse zu Deiner Suche gefunden.</h2>
                    <?php
				}
				?>
			</div>
        </div>
    </body>
</html>
