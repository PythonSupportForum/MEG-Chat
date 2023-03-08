<?
use MinishlinkWebPushWebPush;
require_once("db.php");

echo json_encode($_POST);



$publicKey = "BGW50vjoGBEKXC6onSyjfwZFF5tuLzQEyd4Bu-H5tFm9tp5AmleyjuC1Aec-RzJSCnUHA60aaDWoZkNBbjQHGF8";
$privateKey = "KMeD4lvYxi5VYYS4qwzQxWh-jJXiH_G-Qpr3y_cqK1U";

function getMyUrl(){
  $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
  $server = $_SERVER['SERVER_NAME'];
  $port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
  return $protocol.$server.$port;
}

$webPush = new WebPush([
    "VAPID" => [
        "subject" => getMyUrl(),
        "publicKey" => $publicKey,
        "privateKey" => $privateKey
    ]
]);


?>
