<?php
$output = array();
$return_var = -1;
$message=exec("/var/www/html/MEG-Chat/update.sh 2>&1", $output, $return_var);
echo implode(", ", $output);
echo "<br><br>";
echo "<strong>Status Code: </strong>".$return_var;
echo "<br>";
echo "<strong>Rückgabe: </strong><br>".$message;

if ( $return_var == 0 ) {
    ?>
    <p style="color: green; ">Erfolgreich gespeichert!</p>
    <?php
} else {
	?>
    <p style="color: red; ">Fehler beim Speichern!</p>
    <?php
}
?>
