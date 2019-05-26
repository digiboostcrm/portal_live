<?php

// Upload files on selection
if( isset($_FILES['fileToUpload']) && (count($_FILES['fileToUpload']['name']) > 0))
{
	// Check/Create File Upload Directory
	$record_id 		= $_REQUEST['record_id'];
	$field_name 	= $_REQUEST['file_input_id'];
	$uploaddir		= $_SERVER['DOCUMENT_ROOT']."/upload/multi_".$record_id."/".$field_name;
	if (!is_dir($uploaddir)) {
		mkdir($uploaddir , 0777 , true);
	}
	
	$count = count($_FILES['fileToUpload']['name']);
	
	for ($i = 0; $i < $count; $i++) 
	{
		$file_name		= $_FILES['fileToUpload']['name'][$i];
		$tmp_name		= $_FILES['fileToUpload']['tmp_name'][$i];
		
		move_uploaded_file($tmp_name, $uploaddir."/".$file_name);
		$msg .= " Uploaded to: ".$uploaddir;
	}
	echo json_encode("#UPLOADED SUCCESS# " . $msg);
	
}


// Remove File
if($_POST['file_name'] != '')
{
	$file = trim($_POST['file_name']);
	// Delete File from uploaded folder 
	$file_path = $_SERVER['DOCUMENT_ROOT'].'/upload/multi_'.$_POST['record_id'].'/'.$_POST['field_id'].'/'.$file;
	if(file_exists($file_path))
	{
		unlink($file_path);	
		echo json_encode("#FILE DELETED#");
	}
	else{
		echo json_encode("#FILE DOES NOT EXISTS. PATH = " . $file_path);
	}
}

?>