<?
require_once("db.php");

if(!isset($_SESSION['pupil'])){
	return;
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

$stmtMessage = $db->prepare("INSERT INTO ".DBTBL.".chats_messages (chat, author, text) VALUES (:chat, :author, :text); ");
$stmtMessage->execute(array('chat' => $chat_data['id'], 'author' => $member['pupil'], 'text' => $_POST['text']));
?>
