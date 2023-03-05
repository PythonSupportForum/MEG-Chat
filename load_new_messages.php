<?
require_once("db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
}

$chat = $_POST['chat'];

$chat_data = false;
$member = false;

$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE id = :id; ");
$stmtData->execute(array('id' => $chat));
$row = $stmtData->fetchObject();
if($row){
	$chat_data = (array)$row;
}

if($chat_data){
    if($chat_data['public'] != 1){
		$has_access = false;
	    if(isset($_SESSION['pupil'])){
			$stmtMember = $db->prepare("SELECT * FROM ".DBTBL.".chats_members WHERE pupil = :pupil AND chat = :chat;");
		    $stmtMember->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $chat_data['id']));
		    if($member = $stmtMember->fetchObject()){
				$member = (array)$member;
				$has_access = true;	
			}
		}
		if(!$has_access){
			$chat_data = false;
		}
	}
}

if($chat_data && isset($_SESSION['pupil']) && !$member){
	$stmtInsertMember = $db->prepare("INSERT INTO ".DBTBL.".chats_members (pupil, chat) VALUES (:pupil, :chat);");
    $stmtInsertMember->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $chat_data['id']));
    
    $stmtMember = $db->prepare("SELECT * FROM ".DBTBL.".chats_members WHERE pupil = :pupil AND chat = :chat;");
    $stmtMember->execute(array('pupil' => $_SESSION['pupil'], 'chat' => $chat_data['id']));
    if($member = $stmtMember->fetchObject()){
		$member = (array)$member;
	}
}

if(!$chat_data) return;

$stmtMessage = $db->prepare("SELECT * FROM ".DBTBL.".chats_messages WHERE chat = :chat AND id > :last; ");
$stmtMessage->execute(array('chat' => $chat_data['id'], 'last' => $_POST['last']));

$messages = array();
$last_id = -1;
if($member) $last_id = $member['last_readed_message'];
while($m = $stmtMessage->fetchObject()){
	$m = (array)$m;
	$stmtAuthor = $db->prepare("SELECT id, fullname as username, avatar FROM ".DBTBL.".pupils WHERE id = :pupilId;");
	$stmtAuthor->execute(array('pupilId' => $member['pupil']));
	if($stmtAuthor->rowCount() == 0) continue;
	$last_id = $m['id'];
	
	$messages[] = array('text' => $m['text'], 'time' => $m['time'], 'id' => $m['id'], 'author' => (array)$stmtAuthor->fetchObject(), 'new' => ($m['id'] > $member['last_readed_message']));
}
$stmtMember = $db->prepare("UPDATE ".DBTBL.".chats_members SET last_readed_message = :last WHERE id = :id;");
$stmtMember->execute(array('id' => $member['id'], 'last' => $last_id));

echo json_encode($messages);
?>
