<?
$out = array();
$status = -1;
$message=exec("echo hallo", $output, $return_var);
echo implode(", ", $output);
echo "<br>";
echo "<strong>Status Code: </strong>".$return_var;
echo "<br>";
echo "<strong>Rückgabe: </strong><br>".$message;

if ( $status == 0 ) {
    ?>
    <p style="color: green; ">Erfolgreich gespeichert!</p>
    <?
} else {
	?>
    <p style="color: red; ">Fehler beim Speichern!</p>
    <?
}
?>
