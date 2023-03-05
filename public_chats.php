
<?
if(isset($_SESSION['pupil'])){
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE public = 0 AND id IN (SELECT chat FROM ".DBTBL.".chats_members WHERE pupil = :pupil);");
	$stmtData->execute(array('pupil' => $pupil_data['id']));
	if($stmtData->rowCount() > 0){
?>
<div style="margin-top: 20px; max-width: 100%; width: auto; height: auto; float: left;" class="private_chats_container" id="private_chats_container">
	<h2>Meine Chatgruppen:</h2>
	<?
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
		<div class="chatgruppe" onclick="page_navigate('/chat/<? echo htmlspecialchars($row['id']); ?>', '#chat_container');">
		    <div style="width: 100%; min-height: 40px; height: auto; ">
			    <div style="height: auto; width: 100%; min-height: 40px; position: relative; ">
					<div style="width: calc( 100% - 120px ); ">
			            <h4 style="margin: 0; padding: 0; font-size: 18px; "><? echo htmlspecialchars($row['name']); ?></h4>
			            <h6 style="margin: 0; padding: 0; font-size: 14px; font-weight: small; "><? echo htmlspecialchars($row['description']); ?></h6>
			        </div>
			        <? if($count > 0){ ?>
				    <div style="position: absolute; right: 0px; top: 0px; min-height: 40px; height: auto; width: 100px; " class="centriert">
				        <div style="height: 90%; width: 80%; background-color: red; color: white; border-radius: 10px; font-size: 24px; " class="centriert"><? echo htmlspecialchars($count); ?></div>
				    </div>
				    <? } ?>
			    </div>
		    </div>
		</div>
	<? } ?>
</div>
<?	
} }
?>
<div style="margin-top: 20px; max-width: 100%; width: auto; height: auto; float: left; " class="public_chats_container" id="public_chats_container">
	<h2>Öffentliche Chattgruppen:</h2>
	<?
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE public = 1; ");
	$stmtData->execute();
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
		<div class="chatgruppe" onclick="page_navigate('/chat/<? echo htmlspecialchars($row['id']); ?>', '#chat_container');">
		    <div style="width: 100%; min-height: 40px; height: auto; ">
			    <div style="height: auto; width: 100%; min-height: 40px; position: relative; ">
					<div style="width: calc( 100% - 120px ); ">
			            <h4 style="margin: 0; padding: 0; font-size: 18px; "><? echo htmlspecialchars($row['name']); ?></h4>
			            <h6 style="margin: 0; padding: 0; font-size: 14px; font-weight: small; "><? echo htmlspecialchars($row['description']); ?></h6>
			        </div>
			        <? if($count > 0){ ?>
				    <div style="position: absolute; right: 0px; top: 0px; min-height: 40px; height: auto; width: 100px; " class="centriert">
				        <div style="height: 90%; width: 80%; background-color: red; color: white; border-radius: 10px; font-size: 24px; " class="centriert"><? echo htmlspecialchars($count); ?></div>
				    </div>
				    <? } ?>
			    </div>
		    </div>
		</div>
	<? } ?>
</div>
