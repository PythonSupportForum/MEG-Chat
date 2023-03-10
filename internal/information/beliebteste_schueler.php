<div style="margin-top: 20px; width: max( calc( 100% - 550px ), 400px ); max-width: calc( 100% - 40px ); height: auto; min-height: 480px; float: left; clear: none; ">
	<h2 style="margin-left: 20px; ">Beliebstete Schüler:</h2>
    <?php
	$stmtData = $db->prepare("SELECT ".DBTBL.".pupils.*, COUNT(".DBTBL.".pupils_votes.s_to) AS rating_count, COALESCE(SUM(points),0) as rating FROM ".DBTBL.".pupils LEFT JOIN ".DBTBL.".pupils_votes ON ".DBTBL.".pupils.id = ".DBTBL.".pupils_votes.s_to WHERE activated = 1 GROUP BY ".DBTBL.".pupils.id ORDER BY rating DESC LIMIT 20;");
	$stmtData->execute();
	while($row = $stmtData->fetchObject()){ $row = (array)$row; ?>
		<a href="javascript:page_navigate('/schueler/<?php echo htmlspecialchars($row['id']); ?>');" style="color: black; text-decoration: none; "><div class="schueler_container">
		    <div style="height: calc( 100% - 60px ); width: 100%; margin-top: 10px; " class="centriert">
		        <img loading="lazy" style="width: 130px; height: 130px; border-radius: 50%; " src="<?php echo htmlspecialchars(empty($row['avatar']) ? "/resources/images/avatar.png" : $row['avatar']); ?>">
		    </div>
		    <div style="width: 100%; height: 40px; word-wrap: break-word; " class="centriert">
		        <h3 style="word-wrap: break-word;"><?php echo htmlspecialchars($row['fullname']); ?></h3>
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
    <?php } ?>
	<button style="margin-top: 50px; width: 100%; height: 40px; background-color: darkslategray; color: white; " onclick="page_navigate('/schueler');">Alle Schüler anzeigen</button>
</div>
