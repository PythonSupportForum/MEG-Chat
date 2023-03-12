<?php
require_once("../internal/logic/db.php");

if(!isset($_SESSION['pupil'])){
	echo "Bitte melde dich erneut an, um Bilder hochladen zu können.";
	return exit();
}

$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
$stmtCheck->execute(array('id' => $_SESSION['pupil']));
$pupil_data = (array)$stmtCheck->fetchObject();

$value = trim($_POST['data']);

if(!getimagesize($value)){
    echo "Das Bild ist nicht gültig. Bitte überprüfe deine Datei und versuche es erneut.";
    return exit();
}

if (!file_exists("uploads")) {
    mkdir("uploads", 0770, true);
}
$path = "uploads/".$pupil_data['id']."_".rand(100000,100000000);
file_put_contents($path, file_get_contents($value));
$path = "/".$path;

$stmtInsert = $db->prepare("INSERT INTO ".DBTBL.".pictures (path) VALUES (:path);");
$stmtInsert->execute(array('path' => $path));

?>
