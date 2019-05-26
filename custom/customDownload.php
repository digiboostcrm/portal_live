<?php

$img = explode(".",$_REQUEST['type']);
$legn = sizeof($img) - 1;
if($_REQUEST['des'] == 'caseAttachment' ){
	$local_location = "upload/case_attachement/";
	$filepath = $local_location.$_REQUEST['id']."";
	download($filepath, $img[$legn] , $_REQUEST['type']);
}else if($_REQUEST['des'] == 'updateAttachment'){
	$local_location = "upload/update_attachement/";
	$filepath = $local_location.$_REQUEST['id'];
	download($filepath , $img[$legn] , $_REQUEST['type']);
}

function download($filepath, $type, $name){
	    // Process download
	    header("Pragma: public");
        header("Cache-Control: maxage=1, post-check=0, pre-check=0");
		header("Content-Type: image/png");
		header("Content-Disposition: attachment; filename=\"" . $name . "\";");
        // disable content type sniffing in MSIE
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($filepath));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        set_time_limit(0);
        while (ob_get_level() && @ob_end_clean()) {
            ;
        }
        readfile($filepath);
		exit;	
	
}
?>