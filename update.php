<?
$output = array();
$return_var = -1;
$message=exec("bash /var/www/html/MEG-Chat/update.sh", $output, $return_var);
echo implode(", ", $output);
echo "<br>";
echo "<strong>Status Code: </strong>".$return_var;
echo "<br>";
echo "<strong>Rückgabe: </strong><br>".$message;

if ( $return_var == 0 ) {
    ?>
    <p style="color: green; ">Erfolgreich gespeichert!</p>
    <?
} else {
	?>
    <p style="color: red; ">Fehler beim Speichern!</p>
    <?
}
?>
