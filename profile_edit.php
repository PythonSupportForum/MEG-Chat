<?
require_once("db.php");

if(!isset($_SESSION['pupil'])){
	return;
}

$stmtCheck = $db->prepare("SELECT * FROM ".DBTBL.".pupils WHERE id = :id;");
$stmtCheck->execute(array('id' => $_SESSION['pupil']));
$pupil_data = (array)$stmtCheck->fetchObject();

$key = trim($_POST['key']);
$value = trim($_POST['value']);

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
	    echo "Die eingegebene Email Adresse ist ungültig! Bitte überprüfe Deine Eingabe und versuche es erneut.";	
	}
} else if($key == "avatar"){
	if(!getimagesize($value)){
        echo "Die eingegebene URL verweist auf kein gültiges Bild. Bitte überprüfe Deine Eingabe und versuche es erneut.";
	} else {
		if (!file_exists("uploads")) {
		    mkdir("uploads", 0770, true)
		}
		$path = "uploads/".$pupil_data['id']."_".rand(100000,100000000);
		file_put_contents($path, file_get_contents($value));
		list($width, $height) = getimagesize($path);
		$thumb = imagecreatetruecolor(255, 255);
		$source = imagecreatefromjpeg($path);
		imagecopyresized($thumb, $source, 0, 0, 0, 0, 255, 255, $width, $height);
		imagepng($thumb,$path,3);
        $pupil_data['avatar'] = "/".$path;
	}
} 

$stmtEdit = $db->prepare("UPDATE ".DBTBL.".pupils SET fullname = :fullname, about_me = :about_me, email = :email, avatar = :avatar WHERE id = :id;");
$stmtEdit->execute(array('id' => $pupil_data['id'], 'fullname' => $pupil_data['fullname'], 'email' => $pupil_data['email'], 'about_me' => $pupil_data['about_me'], 'avatar' => $pupil_data['avatar']));
?>
