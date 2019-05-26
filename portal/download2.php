<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
global $db;

if(!isset($_REQUEST['file_path']) && empty($_REQUEST['file_name'])) {
    die("Not a Valid Entry Point");
}
else {
    require_once("data/BeanFactory.php");
    $file_type=''; // bug 45896
    require_once("data/BeanFactory.php");
    ini_set('zlib.output_compression','Off');//bug 27089, if use gzip here, the Content-Length in header may be incorrect.
    // cn: bug 8753: current_user's preferred export charset not being honored
   
	if(empty($current_language)) {
		$current_language = $sugar_config['default_language'];
	}

    $app_strings = return_application_language($current_language);
    $mod_strings = return_module_language($current_language, 'ACL');
   
	$path = $_REQUEST['file_path']."/".$_REQUEST['field_name']."/".$_REQUEST['file_name'];
	
    $local_location = "../upload/{$path}";
			$mime_type = mime_content_type($_REQUEST['file_name']);
        $download_location = $local_location;
		$name = isset($_REQUEST['file_name'])?$_REQUEST['file_name']:'';
       	
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']))
        {
            $name = urlencode($name);
            $name = str_replace("+", "_", $name);
        }

        header("Pragma: public");
        header("Cache-Control: maxage=1, post-check=0, pre-check=0");
		header('Content-type: ' . $mime_type);
		header("Content-Disposition: attachment; filename=\"".$name."\";");
	
	  	// disable content type sniffing in MSIE
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($local_location));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        set_time_limit(0);

        // When output_buffering = On, ob_get_level() may return 1 even if ob_end_clean() returns false 
        // This happens on some QA stacks. See Bug#64860
        while (ob_get_level() && @ob_end_clean());

        readfile($download_location);
        

/*   // apply download for all files  
	
    if(!file_exists( $local_location ) || strpos($local_location, "..")) {

        if(isset($image_field)) {
            header("Content-Type: image/png");
            header("Content-Disposition: attachment; filename=\"No-Image.png\"");
            header("X-Content-Type-Options: nosniff");
            header("Content-Length: " . filesize('include/SugarFields/Fields/Image/no_image.png'));
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
            set_time_limit(0);
            readfile('include/SugarFields/Fields/Image/no_image.png');
            die();
        }else {
            die($app_strings['ERR_INVALID_FILE_REFERENCE']);
        }
    } else {
        
		$mime_type = mime_content_type($_REQUEST['file_name']);
        $download_location = $local_location;
		$name = isset($_REQUEST['file_name'])?$_REQUEST['file_name']:'';
       	
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']))
        {
            $name = urlencode($name);
            $name = str_replace("+", "_", $name);
        }

        header("Pragma: public");
        header("Cache-Control: maxage=1, post-check=0, pre-check=0");
		header('Content-type: ' . $mime_type);
		header("Content-Disposition: attachment; filename=\"".$name."\";");
	
	  	// disable content type sniffing in MSIE
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($local_location));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        set_time_limit(0);

        // When output_buffering = On, ob_get_level() may return 1 even if ob_end_clean() returns false 
        // This happens on some QA stacks. See Bug#64860
        while (ob_get_level() && @ob_end_clean());

        readfile($download_location);
    }
	
	
	*/
}
