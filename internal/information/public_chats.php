<?php
if(isset($_SESSION['pupil'])){
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".chats WHERE public = 0 AND id IN (SELECT chat FROM ".DBTBL.".chats_members WHERE pupil = :pupil);");
	$stmtData->execute(array('pupil' => $pupil_data['id']));
	if($stmtData->rowCount() > 0){
?>
<div style="margin-top: 20px; max-width: 100%; width: auto; height: auto; float: left; clear: none;" class="private_chats_container" id="private_chats_container">
	<h2 style="margin-left: 20px; ">Meine Chatgruppen:</h2>
    <?php
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
		<div class="chatgruppe" onclick="page_navigate('/chat/<?php echo htmlspecialchars($row['id']); ?>', '#chat_container'); ">
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
    <?php } ?>
</div>
        <?php
} }
?>
<div style="margin-top: 20px; max-width: 100%; width: auto; height: auto; float: left; clear: none; " class="public_chats_container" id="public_chats_container">
	<h2 style="margin-left: 20px; ">Öffentliche Chattgruppen:</h2>
    <?php
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
		<div class="chatgruppe" onclick="page_navigate('/chat/<?php echo htmlspecialchars($row['id']); ?>', '#chat_container');">
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
    <?php } ?>
</div>
