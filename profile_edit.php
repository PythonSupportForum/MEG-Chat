<?
require_once("db.php");

if(!isset($_SESSION['pupil'])){
	return;
}

$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
$stmtCheck->execute(array('id' => $_SESSION['pupil']));
$pupil_data = (array)$stmtCheck->fetchObject();

$key = $_POST['key'];
$value = $_POST['value'];

if($key == "about_me"){
    $pupil_data['about_me'] = $value;
} else if($key == "email"){
	if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
		$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE LOWER(email) = LOWER(:email) OR LOWER(fullname) = LOWER(:email);");
		$stmtCheck->execute(array('email' => $value));
		if($stmtCheck->rowCount() != 0){
			echo "Es ist bereits ein anderer Schüler mit dieser Email Adresse regestriert. Bitte melden Sie diesen Vorfall einem Adminstrator wenn Sie denken, dass diese Person nicht unter dieser Email Adresse regestriert sein darf.";
			return exit();
		}
        $pupil_data['email'] = $value;
    } else {
	    echo "Die eingegebene Email Adresse ist ungültig! Bitte überprüfen Sie Ihre EIngaben und versuchen Sie es erneut.";	
	}
}

$stmtEdit = $db->prepare("UPDATE ".DBTBL.".pupils SET fullname = :fullname, about_me = :about_me, email = :email WHERE id = :id;");
$stmtEdit->execute(array('id' => $pupil_data['id'], 'fullname' => $pupil_data['fullname'], 'email' => $pupil_data['email'], 'about_me' => $pupil_data['about_me']));
?>
