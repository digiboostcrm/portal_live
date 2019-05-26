<?php
	$data = $_REQUEST['data'];
	$jsonData =  htmlspecialchars_decode($data);
	$id = $_REQUEST['id'];
	global $db;
	$query = "UPDATE opportunities SET add_notes = '$jsonData' WHERE id = '$id'";
	$db->query($query);
	echo $jsonData;

?>