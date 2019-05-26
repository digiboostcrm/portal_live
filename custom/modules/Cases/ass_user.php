<?php
	$id = $_REQUEST['id'];
	$user_id = $_REQUEST['user_id'];
	global $db;
	$query = "UPDATE cases SET assigned_user_id = '$user_id' WHERE id = '$id'";
	$db->query($query);
	echo $user_id;

?>