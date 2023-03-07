<div style="margin-top: 20px; width: max( calc( 100% - 550px ), 500px ); max-width: 100%; height: auto; min-height: 480px; float: left; clear: none; ">
	<h2 style="margin-left: 20px; ">Neuigkeiten aus unserem Blog:</h2>
	<?
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".blog ORDER BY time DESC;");
	$stmtData->execute();
	while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
		<a href="javascript:page_navigate('/blog/<? echo htmlspecialchars($row['id']); ?>');" style="color: black; float: left; "><div class="blog_entry">
		    <div style="width: 100%; height: 100px; margin-top: 20px; " class="centriert">
		        <img style="height: 100%; width: auto; " src="<? echo htmlspecialchars($row['image']); ?>">
		    </div>
		    <div style="width: 100%; margin-top: 20px; " class="centriert">
		        <h2><? echo htmlspecialchars($row['header']); ?></h2>
		    </div>
		    <div style="width: calc( 100% - 10px ); margin-top: 20px; margin-left: 5px; ">
		        <p style="font-size: 16px; "><? echo htmlspecialchars($row['text']); ?></p>
		    </div>
		</div></a>
	<? } ?>
</div>
