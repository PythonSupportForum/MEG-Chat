<div style="margin-top: 60px; width: calc( 100% - 40px ); height: auto; min-height: 480px; float: left; clear: none; ">
	<h2 style="margin-left: 20px; ">Neuigkeiten aus unserem Blog:</h2>
    <?php
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".blog ORDER BY time DESC;");
	$stmtData->execute();
	while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
		<a href="javascript:page_navigate('/blog/<?php echo htmlspecialchars($row['id']); ?>');" style="color: black; float: left; width: auto; max-width: 100%; "><div class="blog_entry">
		    <div style="width: 100%; height: 100px; margin-top: 20px; " class="centriert">
		        <img style="height: 100%; width: auto; " src="<?php echo htmlspecialchars($row['image']); ?>">
		    </div>
		    <div style="width: 100%; margin-top: 20px; " class="centriert">
		        <h2><?php echo htmlspecialchars($row['header']); ?></h2>
		    </div>
		    <div style="width: calc( 100% - 10px ); margin-top: 20px; margin-left: 5px; ">
		        <p style="font-size: 16px; "><?php echo htmlspecialchars($row['text']); ?></p>
		    </div>
		</div></a>
    <?php } ?>
</div>
