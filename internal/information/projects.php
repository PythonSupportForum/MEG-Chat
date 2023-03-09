<div style="margin-top: 20px; width: max( calc( 100% - 550px ), 500px ); max-width: 100%; height: auto; min-height: 480px; float: left; clear: none; ">
	<h2 style="margin-left: 20px; ">Beste Projektarbeiten:</h2>
	<?
	$stmtData = $db->prepare("SELECT * FROM ".DBTBL.".projects ORDER BY id DESC;");
	$stmtData->execute();
	while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
		<a href="<? echo htmlspecialchars($row['url']); ?>" style="color: black; "><div style="margin: 20px; margin-top: 30px; max-width: calc( 100% - 20px ); width: auto; ">
		    <div style="width: 100%; margin-top: 10px; ">
		        <h3 style="color: lightblue; "><? echo htmlspecialchars($row['name']); ?></h3>
		    </div>
		    <div style="width: calc( 100% - 10px ); margin-top: 10px; margin-left: 5px; ">
		        <p style="font-size: 14px; "><? echo htmlspecialchars($row['description']); ?></p>
		    </div>
		    <div style="width: calc( 100% - 10px ); margin-top: 10px; margin-left: 5px; ">
		        <p style="font-size: 14px; "><strong>Gemacht von:</strong> <? echo htmlspecialchars($row['authors']); ?></p>
		        <p style="font-size: 14px; "><strong>Im Kurs:</strong> <? echo htmlspecialchars($row['kurs']); ?></p>
		    </div>
		</div></a>
	<? } ?>
</div>
