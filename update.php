<?
$message=exec("sudo -u root -S bash /var/www/html/MEG-Chat/update.sh < /home/tilo2/password.txt", $output, $return_var);
echo implode(", ", $output);
echo "<br>";
echo $return_var;
echo "<br>";
echo $message;

?>
<h1>OLEOLEOLE</h1>
<p style="color: green; ">Erfolgreich gespeichert!</p>
