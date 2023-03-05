<?
$message=shell_exec("sudo -u root -S bash /var/www/html/MEG-Chat/update.sh < /home/tilo2/password.txt");
echo $message;
?>
<p style="color: green; ">Erfolgreich gespeichert!</p>
