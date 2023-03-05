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
if(!$chat_data) return;

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

$stmtMessage = $db->prepare("INSERT INTO ".DBTBL.".chats_messages (chat, author, text) VALUES (:chat, :author, :text); ");
$stmtMessage->execute(array('chat' => $chat_data['id'], 'author' => $member['pupil'], 'text' => $_POST['text']));
?>
