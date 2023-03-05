<?
$message=shell_exec("sudo -u root -S bash /var/www/html/MEG-Chat/update.sh < /home/tilo2/password.txt");
print_r($message);
?>
<p>Erfolgreich gespeichert!</p>
