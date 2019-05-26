<?php
$date = $timedate->now();
$id = $_REQUEST['user_id'];
$ip = get_client_ip();
/*
$date = new DateTime($date);
$date->modify('+1 min');
$date = $date->format('d/m/Y H:i'); // 2014-01-04
*/
//$date = strtotime(date("m/d/Y h:i:s", time() + 60));
$date = time() + 60;

$data = "{\"IP\" : \"$ip\" , \"EXPIRY\": \"$date\" , \"USER_ID\" : \"$id\"}";

$fileName = md5(uniqid(rand(), true));

$myfile = fopen("custom/modules/Contacts/loginFiles/$fileName.txt", "w") or die("Unable to open file!");
$txt = "$data";
fwrite($myfile, $txt);
fclose($myfile);


function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

echo $fileName;die;
?>

