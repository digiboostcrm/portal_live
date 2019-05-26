<?php

	
if(empty($_REQUEST['method'])) {
	header('HTTP/1.1 400 Bad Request');
	$response = "method is required.";
	$json = getJSONobj();
	echo $json->encode($response);
}


//load license validation config
require_once('modules/'.$currentModule.'/license/OutfittersLicense.php');

if($_REQUEST['method'] == 'validate') {
	OutfittersLicense::validate();
} else if($_REQUEST['method'] == 'change') {
	OutfittersLicense::change();
} else if($_REQUEST['method'] == 'add') {
    OutfittersLicense::add();
}
