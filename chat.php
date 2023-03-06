<?
require_once("db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}

$chat = $_GET['chat'];

$chat_data = false;
$member = false;

$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE id = :id; ");
$stmtData->execute(array('id' => $chat));
$row = $stmtData->fetchObject();
if($row){
	$chat_data = (array)$row;
}

if($chat_data){
	if(isset($_SESSION['pupil'])){
		$stmtMember = $db->prepare("SELECT * FROM ".DBTBL.".chats_members WHERE pupil = :pupil AND chat = :chat;");
	    $stmtMember->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $chat_data['id']));
	    if($member = $stmtMember->fetchObject()){
			$member = (array)$member;
		} else {
			$stmtInsertMember = $db->prepare("INSERT INTO ".DBTBL.".chats_members (pupil, chat) VALUES (:pupil, :chat);");
		    $stmtInsertMember->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $chat_data['id']));
		    
		    $stmtMember = $db->prepare("SELECT * FROM ".DBTBL.".chats_members WHERE pupil = :pupil AND chat = :chat;");
		    $stmtMember->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $chat_data['id']));
		    if($member = $stmtMember->fetchObject()){
				$member = (array)$member;
			}
		}
	}
	if($chat_data['public'] != 1){
		if(!$member) $chat_data = false;
	}
}

if($chat_data){
	$stmtMemberCount = $db->prepare("SELECT COUNT(id) as count FROM ".DBTBL.".chats_members WHERE chat = :chat; ");
	$stmtMemberCount->execute(array('chat' => $chat_data['id']));
	$member_count = ((array)$stmtMemberCount->fetchObject())['count'];
	
	$stmtMessagesCount = $db->prepare("SELECT COUNT(id) as count FROM ".DBTBL.".chats_messages WHERE chat = :chat; ");
	$stmtMessagesCount->execute(array('chat' => $chat_data['id']));
	$messages_count = ((array)$stmtMessagesCount->fetchObject())['count'];
}
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MEG Chat | Chat | <? echo htmlspecialchars($chat_data ? $chat_data['name'] : "Nicht Gefunden"); ?></title>
        <meta name="description" content="<? echo htmlspecialchars($chat_data ? $chat_data['description'] : "Dieser Chat exestiert nicht, oder Sie haben keinen Zugriff darauf!"); ?>">
        <meta name="keywords" lang="de" content="meg, max, ernst, gymnasium, max ernst gymnasium, brühl, chat, online, schueler, chatten, austauschen, hausaufgaben, fragen, blog, artikel, austausch, kontakt, neues">
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
    <body style="background-color: #303030; color: lightgray; ">
        <div style="float: left; width: 540px; max-width: 100%; height: auto; max-height: 100%; overflow-x: hidden; overflow-y: auto; " class="no_scrollbar">
			<div style="width: 100%; height: 145px; margin-top: 20px; ">
			    <div style="width: 50%; height: 100%; float: left; cursor: pointer; " class="centriert" onclick="page_navigate('/');">
			        <div style="width: 100%; height: 100%; " class="centriert"><img style="height: 100%; width: auto; max-width: 100%; " src="/logo.png" alt="MEG Chat Logo"></div>
			    </div>
			    <div style="width: 50%; height: 100%; float: right; " class="centriert">
				    <div style="width: calc( 100% - 20px ); text-align: center; height: auto; ">
		                <?
						if(isset($_SESSION['pupil'])){
							?>
							<h2 style="margin-top: 5px; font-size: 14px; word-wrap: break-word; ">Du bist angemeldet als <? echo htmlspecialchars($pupil_data['fullname']); ?>!</h2>
							<?
							if($pupil_data['activated'] == 0){
								?>
								<p style="color: red; font-size: 10px; ">Dein Account ist noch nicht freigeschaltet worden. Bitte gedulte dich einige Zeit oder Kontaktiere einen Administrator. Wir werden deine Identität Prüfen und den Account anschließend freischalten. Wenn nicht Pech gehabt su Opfer!</p>
								<?
						    }
							?>
							<div style="width: 100%; height: auto; margin-top: 10px; ">
							    <button onclick="page_navigate('/schueler/<? echo htmlspecialchars($pupil_data['id']); ?>');" style="background-color: blue; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Einstellungen</button>
							    <button onclick="window.location.href='/logout.php';" style="background-color: red; color: white; font-size: 16px; width: 100%; height: 25px; margin-top: 10px; ">Abmelden</button>
							</div>
							<?
		                } else { ?>
							<div style="width: 100%; height: auto; ">
		                        <button onclick="page_navigate('/login.php');" style="width: 100%; height: 25px; ">Anmelden</button>
		                        <button onclick="page_navigate('/register.php');" style="width: 100%; height: 50px; margin-top: 10px; ">Mich als Schüler hinzufügen</button>
		                    </div>
		                <? } ?>
	                </div>
			    </div>
			</div>
            <div style="width: 100%; height: auto; margin-top: 20px; ">
				<div id="all_container">
					<? require("public_chats.php"); ?>
	            </div>
	        </div>
        </div>
        <div style="float: left; width: calc( 100% - 542px ); min-width: 350px; max-width: 100%; text-align: center; height: 100%; " id="chat_container">
			<? if(!$chat_data){
			    ?>
			    <h1>Entweder dieser Chat exestiert nicht oder zu hast keinen Zugriff darauf. Sollte dieses problem weiterhin auftauchen melde dich bitte bei einem Administrator.!</h1>
			    <?
			} else { ?>
				<div style="height: auto; min-height: 110px; margin-top: 6px; " class="centriert">
					<div style="width: auto; height: 100%; " class="centriert">
						<div style="height: 110px; width: auto; float: left; " class="centriert">
							<div>
				                <h1 style="margin-top: 0px; "><? echo htmlspecialchars($chat_data['name']); ?></h1>
				                <div style="width: 100%; height: auto; margin-top: 10px; " class="centriert">
				                     <h2 style="margin: 0; padding: 0;"><? echo htmlspecialchars($chat_data['description']); ?></h2>
				                </div>
				            </div>
			            </div>
			            <div style="height: 110px; width: auto; float: left; border-left: 1px solid white; margin-left: 20px; " class="centriert">
			                <div style="margin-left: 20px; text-align: left; ">
			                    <h4><? echo htmlspecialchars($member_count); ?> Mitglieder</h4>
			                    <h4 style="margin-top: 10px; "><span id="chat_messages_count"><? echo htmlspecialchars($messages_count); ?></span> Nachrichten</h4>
			                </div>
			            </div>
		            </div>
                </div>
                <div style="width: 100%; height: calc( 100% - 155px ); min-height: 200px; max-height: 100%; margin-top: 20px; " class="centriert">
                    <div style="height: 100%; min-width: 320px; width: 80%; max-width: 95%; position: relative;">
                        <div style="position: absolute; top: 0px; left: 0px; right: 0px; bottom: 52px; overflow-x: hidden; overflow-y: auto; " id="chat_inner_data_container" class="no_scrollbar">
                            <div style="width: 100%; height: auto; overflow: hidden; " id="chat_inner_data"></div>
                            <div style="width: 100%; height: 25px; "></div>
                        </div>
                        <?
                        if(!isset($_SESSION['pupil'])){
							?>
							<div style="position: absolute; bottom: 0px; right: 0px; left: 0px; min-height: 50px; height: auto; " class="centriert">
							    <p style="color: red; ">Um selber Nachrichten in diesen Chat schreiben zu können, melde dich bitte an oder regestriere dich. Der Zugang ist nur für Schüler des MEGs erlaubt!</p>
							</div>
							<?
						} else {
						    ?>
						    <textarea rows="1" onkeydown="message_input_keydown(event);" id="private_message_text" style="position: absolute; bottom: 0px; right: 0px; left: 0px; height: 30px;  font-size: 24px; text-align: left; resize: none; background-color: transparent; " class="text" placeholder="Meine Nachricht.."></textarea>
						    <?	
						}
						?>
                    </div>
                </div>
            <? } ?>
        </div>
        <? if($chat_data){ ?>
        <script>
            window.last_message_id = -1;
            window.loaded_messages_count = 0;
            window.chat_id = Number(window.location.href.split("/")[window.location.href.split("/").length-1]);
            
			window.message_input_keydown = function(evt) {
				if(document.getElementById("private_message_text").value.split("\n").length < document.getElementById("private_message_text").rows){
					document.getElementById("private_message_text").rows = document.getElementById("private_message_text").value.split("\n").length;
					document.getElementById("private_message_text").style.height = (document.getElementById("private_message_text").rows*30)+"px";
				}
				
			    evt = evt || window.event;
			    var charCode = evt.keyCode || evt.which;
			    
			    if(!charCode) return;
			    
			    if(evt.shiftKey){
				    if (charCode == 13) {
						if(document.getElementById("private_message_text").rows < 10){
							document.getElementById("private_message_text").rows++;
							document.getElementById("private_message_text").style.height = (document.getElementById("private_message_text").rows*30)+"px";
						}
					}
				} else {
					if (charCode == 13) {
						evt.preventDefault();
						
						var value = document.getElementById("private_message_text").value.trim();
						if(value.length == 0) return;
						
						document.getElementById("private_message_text").value = "";
						document.getElementById("private_message_text").rows = 1;
						document.getElementById("private_message_text").style.height = "30px";
						
						function send_chess_message(){
                            post_request("/send_message.php", {text: value, chat: chat_id});
						}
						send_chess_message();
					}
			    }
			};
			
			window.get_messages_data = async function(){
				if(!document.getElementById("chat_container")){
					setTimeout(function(){
					    get_messages_data();
					}, 200);
				    return;
				}
				if(!("running_chat_reader" in window)) {
					window.running_chat_reader = false;
				}
				if(running_chat_reader) return;
				window.running_chat_reader = true;
				if(chat_id != Number(window.location.href.split("/")[window.location.href.split("/").length-1])){
				    chat_id = Number(window.location.href.split("/")[window.location.href.split("/").length-1]);
				    last_message_id = -1;
				    loaded_messages_count = 0;
				}
				if(last_message_id > -1){
					if(!document.getElementById("message_"+chat_id+"_"+last_message_id)){
						last_message_id = -1;
					}
				}
				post_request("/load_new_messages.php", {chat: chat_id, last: Number(last_message_id)}, function(data){
					setTimeout(function(){
						window.running_chat_reader = false;
					    get_messages_data();
					}, 50);
					data = JSON.parse(data);
					var is_first = (last_message_id == -1);
					var first_new = true;
					data.forEach(function(z){
						if(Number(z.id) <= Number(last_message_id) || document.getElementById("message_"+chat_id+"_"+z.id)) {
						    return;
						}
						if(!document.getElementById("redline")){
						    if(z.new && first_new){
								first_new = false;
								if(is_first || document.hidden){
									var r = document.createElement("div");
									r.style = "width: 100%; height: 1px; background-color: red; margin-top: 10px; ";
									r.id = "redline";
									document.getElementById("chat_inner_data").insertAdjacentHTML("beforeend", r.outerHTML+"<br>");
								}
							}
						} else if(!is_first && !document.hidden){
							document.getElementById("redline").remove();
						}
						
						last_message_id = Number(z.id);
						var ne = document.createElement("div");
						ne.style = "width: 100%; margin-top: 10px; height: auto; min-height: 40px; word-warp: break-word; color: white; text-align: left; font-size: 14px; position: relative; ";
						ne.id = "message_"+chat_id+"_"+z.id;
						var nei = document.createElement("div");
						nei.style = "position: absolute; top: 0px; left: 44px; right: 0px; margin-top: 4px; ";
						var na = document.createElement("u");
						na.innerText = z.author.username;
						na.style = "font-weight: bold; cursor: pointer; ";
						na.addEventListener("click", function(){
							console.log("hi");
							page_navigate("/schueler/"+z.author.id);
						});
						nei.appendChild(na);
						var nt = document.createElement("span");
						nt.style = "margin-left: 10px; ";
						nt.innerText = "\n"+z.text;
						nei.appendChild(nt);
						ne.appendChild(nei);
						var neb = document.createElement("div");
						neb.style = "position: absolute; top: 0px; left: 0px; height: 40px; width: 40px; display: flex; justify-content: center; align-items: center; ";
						var neba = document.createElement("img");
						neba.style = "width: 34px; height: 34px; border-radius: 50%; ";
						neba.src = z.author.avatar || "/resources/images/avatar.png";
						neb.appendChild(neba);
						ne.appendChild(neb);
						
						if(!document.getElementById("chat_inner_data") || !document.getElementById("chat_inner_data_container")) return;
						
						document.getElementById("chat_inner_data").insertAdjacentHTML("beforeend", ne.outerHTML+"<br>");
						document.getElementById("chat_inner_data_container").scrollTop = document.getElementById("chat_inner_data_container").scrollHeight;
						
						loaded_messages_count++;
						
						var messages_count = Number(document.getElementById("chat_messages_count").innerText);
						if(loaded_messages_count > messages_count){
						    messages_count++;
						    document.getElementById("chat_messages_count").innerText = messages_count;
						}
					});
				});
			}
			get_messages_data();
        </script>
        <? } ?>
    </body>
</html>
