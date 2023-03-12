<?php
require_once("../logic/db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}

$s = $_GET['schueler'];

$stmtData = $db->prepare("SELECT ".DBTBL.".pupils.*, COUNT(".DBTBL.".pupils_votes.s_to) AS rating_count, COALESCE(SUM(points),0) as rating FROM ".DBTBL.".pupils LEFT JOIN ".DBTBL.".pupils_votes ON ".DBTBL.".pupils.id = ".DBTBL.".pupils_votes.s_to WHERE pupils.id = :id GROUP BY ".DBTBL.".pupils.id;");
$stmtData->execute(array('id' => $s));
$row = $stmtData->fetchObject();
$s_data = (array)$row;

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
        <title>MEG Chat | Schüler | <?php echo htmlspecialchars($s_data['fullname']); ?></title>
        <meta name="description" content="<?php echo htmlspecialchars($s_data['about_me']); ?>">
        <meta name="keywords" lang="de" content="max ernst gymnasium, meg, schüler, schueler, klasse, informtionen, profil, <?php echo htmlspecialchars($s_data['fullname']); ?>">
        <?php require('../middleware/head.php'); ?>
    </head>
    <body>
		<?php if(!$is_mobile){ ?>
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
        <?php } else { ?>
	    <button onclick="window.history.go(-1); " style="position: fixed; top: 0px; left: 0px; min-height: 50px; height: auto; width:  auto; font-size: 16px; background-color: transparent; font-size: 24px; color: white; border: none; outline: none; ">&#8678;</button>
		<?php } ?>
        <div style="float: left; width: <?php if(!$is_mobile){ ?>calc( 100% - 162px )<?php } else { ?>100%<?php } ?>; min-width: 300px; max-width: 100%; text-align: center;">
            <?php if(!$s_data){
			    ?>
			    <h1>Das Profil dieses Schülers konnte nicht gefunden werden!</h1>
                <?php
			} else { ?>
                <h1><?php echo htmlspecialchars($s_data['fullname']); ?></h1>
                <div style="width: 100%; height: auto; " class="centriert">
                    <img loading="lazy" id="avatar" style="width: 300px; height: 300px; max-width: 100%; max-height: 100%; border-radius: 50%; " src="<?php echo htmlspecialchars(empty($s_data['avatar']) ? "/resources/images/avatar.png" : $s_data['avatar']); ?>">
                </div>
                <div style="width: 100%; height: auto; margin-top: 25px; " class="centriert">
					<div style="width: 500px; max-width: 100%;">
						<div class="tab">
						  <button class="tablinks active" onclick="openTab(event, 'profile')">Profil</button>
						  <button class="tablinks" onclick="openTab(event, 'chats_together')">Gemeinsame Chats</button>
						  <button class="tablinks" onclick="openTab(event, 'contact')">Kontakt</button>
						</div>
						<div id="profile" class="tabcontent" style="display: block; text-align: left; padding-left: 10px; padding-right: 10px; padding-bottom: 10px; ">
						  <h3>Über mich:</h3>
						  <p id="about_me_text"><?php echo htmlspecialchars(empty($s_data['about_me']) ? "noch nichts" : $s_data['about_me']); ?></p>
                            <?php
						  if(isset($_SESSION['pupil'])){
							  if($pupil_data['id'] == $s_data['id']){
								  ?>
								  <button onclick="edit_about_me();">Bearbeiten</button>
                                  <?php
						      }
						  }
						  if(isset($_SESSION['pupil'])){
							  if($pupil_data['id'] == $s_data['id']){
								  ?>
								  <h3>Mein Profilbild ändern:</h3>
								  <button onclick="upload_avatar();">Bild hochladen</button>
								  <button onclick="edit_avatar();">URL auswählen</button>
                                  <?php
						      }
						  }
						  ?>
						  <h3>Beliebtheit:</h3>
						  <div style="width: 100%; height: 20px; font-size: 14px; ">
			                  <div style="width: 100%; text-align: left ">
							      <div style="text-align: left; width: auto; height: auto; ">
                                      <?php if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $s_data['id'] != $pupil_data['id']){ ?><a style="color: white; " href="javascript:void(0); " onclick="event.stopPropagation(); vote('<?php echo htmlspecialchars($s_data['id']); ?>');"><?php } ?>
									  <div class="schueler_vote_count_<?php echo htmlspecialchars($s_data['id']); ?>" style="float: left; color: white; "><?php echo $s_data['rating']; ?></div>
									  <div style="float: left; margin-left: 8px; color: white; ">Stimmen</div>
                                          <?php if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $s_data['id'] != $pupil_data['id']){ ?></a><?php } ?>
								  </div>
							  </div>
						  </div>
                            <?php if(isset($_SESSION['pupil']) && $pupil_data['activated'] == 1 && $s_data['id'] != $pupil_data['id']){ ?>
					      <div style="width: 100%; height: 25px; font-size: 14px; margin-top: 15px; ">
							  <button onclick="event.stopPropagation(); vote('<?php echo htmlspecialchars($s_data['id']); ?>');">Gefällt Mir</button>
						  </div>
                            <?php }?>
						</div>
						<div id="chats_together" class="tabcontent" style="text-align: left; padding-left: 10px; padding-right: 10px; padding-bottom: 10px; ">
                            <?php
						    if(!isset($_SESSION['pupil'])){
								?>
								<h3 style="text-align: center;">Bitte melde dich an um zu sehen, welche Chats du mit <?php echo htmlspecialchars($s_data['fullname']); ?> gemeinsam hast.</h3>
                                <?php
							} else if($pupil_data['id'] == $s_data['id']){
							    ?>
								<h3 style="text-align: center;">Du hast ne Menge Chats mit dir selber xD</h3>
                                <?php
							} else {
								$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE public = 0 AND id IN (SELECT chat FROM ".DBTBL.".chats_members WHERE pupil = :pupil) AND id IN (SELECT chat FROM ".DBTBL.".chats_members WHERE pupil = :pupil2); ");
								$stmtData->execute(array('pupil' => $s_data['id'], 'pupil2' => $pupil_data['id']));
								$no_chats = true;
								while($row = $stmtData->fetchObject()){
									$row = (array)$row;
									$no_chats = false;
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
									<div class="chatgruppe" onclick="page_navigate('/chat/<?php echo htmlspecialchars($row['id']); ?>', '#chat_container'); window.last_message_id = -1;">
									    <div style="width: 100%; min-height: 40px; height: auto; ">
										    <div style="height: auto; width: 100%; min-height: 40px; position: relative; ">
												<div style="width: calc( 100% - 120px ); ">
										            <h4 style="margin: 0; padding: 0; font-size: 18px; "><?php echo htmlspecialchars($row['name']); ?></h4>
										            <h6 style="margin: 0; padding: 0; font-size: 14px; font-weight: small; "><?php echo htmlspecialchars($row['description']); ?></h6>
										        </div>
                                                <?php if($count > 0){ ?>
											    <div style="position: absolute; right: 0px; top: 0px; min-height: 40px; height: auto; width: 100px; " class="centriert">
											        <div style="height: 90%; width: 80%; background-color: red; color: white; border-radius: 10px; font-size: 24px; " class="centriert"><?php echo htmlspecialchars($count); ?></div>
											    </div>
                                                <?php } ?>
										    </div>
									    </div>
									</div>
                                    <?php
								}
								if($no_chats){
									?>
									<h3 style="text-align: center;">Du hast noch keine gemeinsamen Chats mit <?php echo htmlspecialchars($s_data['fullname']); ?>.</h3>
                                    <?php
								}
							}
							?>
						</div>
						
						<div id="contact" class="tabcontent" style="text-align: left; padding-left: 10px; padding-right: 10px; padding-bottom: 10px; ">
						  <p><strong>Email:</strong> <span id="email_text"><?php echo htmlspecialchars($s_data['email']); ?></span> <?php
						  if(isset($_SESSION['pupil'])){
							  if($pupil_data['id'] == $s_data['id']){
								  ?>
								  <button onclick="edit_email();">Bearbeiten</button>
                                  <?php
						      }
						  }
						  ?></p>
						</div>
					</div>
				</div>
            <?php } ?>
        </div>
        <script>
	        window.edit_about_me = function(){
				html_popup("Erzähl etwas über dich..", '<textarea style="width: 100%; height: 200px; resize: none; " id="about_me_editor"><?php echo htmlspecialchars($s_data['about_me']); ?></textarea><button onclick="save_about_me();">Speichern</button>');
			};
			window.save_about_me = function(){
				var value = document.getElementById("about_me_editor").value;
				post_request("/ajax/profile_edit.php", {key: "about_me", value: value}, function(data){
					if(data.length > 2){
					    popup("Fehler!", data);
					} else {
						close_all_popups();
					    page_navigate(window.location.href, "#about_me_text");
					}
				});
				
			}
			window.edit_email = function(){
				html_popup("Email Adresse bearbeiten", '<input type="email" id="email_editor" value="<?php echo htmlspecialchars($s_data['email']); ?>" placeholder="mustermann.max@meg-bruehl.de"></input><button onclick="save_email();">Speichern</button>');
			};
			window.save_email = function(){
				var value = document.getElementById("email_editor").value;
				post_request("/ajax/profile_edit.php", {key: "email", value: value}, function(data){
					if(data.length > 2){
					    popup("Fehler!", data);
					} else {
						close_all_popups();
					    page_navigate(window.location.href, "#email_text");
					}
				});
			}
			window.edit_avatar = function(){
				html_popup("Profilbild ändern", '<input type="text" id="avatar_editor" placeholder="https://example.com/bild.png"></input><button onclick="save_avatar();">Speichern</button>');
			};
			window.save_avatar = function(){
				var value = document.getElementById("avatar_editor").value;
				post_request("/ajax/profile_edit.php", {key: "avatar", value: value}, function(data){
					if(data.length > 2){
					    popup("Fehler!", data);
					} else {
						close_all_popups();
					    document.getElementById("avatar").src = value;
					}
				});
			}
			window.upload_avatar = function(){
				var e = document.createElement("input");
				e.type = "file";
				e.accept = "image/*";
				e.onchange = async function(){
					var file = e.files[0];
					if(!file) return;
				    var dataUrl = await new Promise(resolve => {
				      let reader = new FileReader();
				      reader.onload = () => resolve(reader.result);
				      reader.readAsDataURL(file);
				    });
				    post_request("/ajax/profile_edit.php", {key: "avatar", value: dataUrl}, function(data){
					if(data.length > 2){
					    popup("Fehler!", data);
					} else {
						close_all_popups();
					    document.getElementById("avatar").src = dataUrl;
					}
				});
				};
				e.click();
			};
	    </script>
    </body>
</html> 
