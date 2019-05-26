<?php

	$recID = $_REQUEST['rec_id'];
	$status = $_REQUEST['button_status'];
	$bit = 0;
	if ($status == 'Private'){
		$bit = 1;
	}
	global $db;
	$sql = " UPDATE aop_case_updates SET private_case = $bit WHERE id = '$recID' AND deleted = 0";
	$res = $db->query($sql);
	if($bit == 0)	
		echo "Private";
	else
		echo "Public";

?>