<div style="margin-top: 20px; width: max( calc( 100% - 550px ), 500px ); max-width: 100%; height: auto; min-height: 480px; float: left; clear: none; ">
	<h2 style="margin-left: 20px; ">Beste Projektarbeiten:</h2>
	<?
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".projects ORDER BY time DESC;");
	$stmtData->execute();
	while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
		<a href="<? echo htmlspecialchars($row['url']); ?>" style="color: black; "><div class="project">
		    <div style="width: 100%; margin-top: 20px; " class="centriert">
		        <h2><? echo htmlspecialchars($row['name']); ?></h2>
		    </div>
		    <div style="width: calc( 100% - 10px ); margin-top: 20px; margin-left: 5px; ">
		        <p style="font-size: 16px; "><? echo htmlspecialchars($row['description']); ?></p>
		    </div>
		    <div style="width: calc( 100% - 10px ); margin-top: 20px; margin-left: 5px; ">
		        <p style="font-size: 16px; ">Gemacht von <? echo htmlspecialchars($row['authors']); ?></p>
		        <p style="font-size: 16px; ">Im Kurs: <? echo htmlspecialchars($row['kurs']); ?></p>
		    </div>
		</div></a>
	<? } ?>
</div>

