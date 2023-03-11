<?php
if(!isset($_GET['chat'])) return;

require_once("../internal/logic/db.php");

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

if(!$chat_data) return;
?>
<html>
    <head></head>
    <body>
        <?php
        $stmtMembers = $db->prepare("SELECT ".DBTBL.".pupils.*, COUNT(".DBTBL.".pupils_votes.s_to) AS rating_count, COALESCE(SUM(points),0) as rating FROM ".DBTBL.".pupils LEFT JOIN ".DBTBL.".pupils_votes ON ".DBTBL.".pupils.id = ".DBTBL.".pupils_votes.s_to WHERE pupils.id IN (SELECT pupil FROM ".DBTBL.".chats_members WHERE chat = :chat) GROUP BY ".DBTBL.".pupils.id ORDER BY LOWER(fullname) ASC LIMIT 10000;");
		$stmtMembers->execute(array('chat' => $chat_data['id']));
		while($row = $stmtMembers->fetchObject()){
		    $row = (array)$row;
		    ?>
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
		    <?	
		}
		if($chat_data['public'] == 1){
			?>
			<div style="width: 100%; height: auto; margin-top: 20px; text-align: center; ">Dieser Chat ist öffentlich. Das heißt das Jeder die Nachrichten in diesem Chat lesen kann, auch ohne Mitglied zu sein.</div>
			<?php
		}	
		?>
		<div style="width: 100%; height: 20px; "></div> 
    </body>
</html>
