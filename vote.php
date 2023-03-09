<?
require_once("internal/logic/db.php");

if(isset($_SESSION['pupil'])){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
	$stmtCheck->execute(array('id' => $_SESSION['pupil']));
	$pupil_data = (array)$stmtCheck->fetchObject();
	
	if($pupil_data['activated'] == 0){
		echo "Dein Account wurde noch nicht aktiviert. Bis dahin knast du noch nicht an Abstimmungen teilnehmen. Bitte kontaktiere einen Administrator um den Vorgang zu beschleunigen.";
		return exit();
    }
} else {
    ?>
    Bitte melde dich erneut an um Abstimmen zu können.
    <?	
}

if(isset($_POST['vote']) && isset($_SESSION['pupil']) && $pupil_data['activated'] == 1){
	$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils_votes WHERE s_from = :from AND s_to = :to AND DATE(`time`) = CURDATE();");
	$stmtCheck->execute(array('from' => $_SESSION['pupil'], 'to' => $_POST['vote']));
	if($stmtCheck->rowCount() == 0){
		$stmtInsert = $db->prepare("INSERT INTO ".DBTBL.".pupils_votes (s_from, s_to) VALUES (:from, :to);");
	    $stmtInsert->execute(array('from' => $_SESSION['pupil'], 'to' => $_POST['vote']));
    } else {
	    ?>
	    Du kanst für Jeden Schüler täglich nur einmal Abstimmen. Bitte warte bis morgen.
	    <?	
	}
}
?>
