<?php
Class fileUpload{
	public function upload_files(&$bean, $event, $arguments){
		global $sugar_config;
		/*Add Ticket Name in Recently Activity*/
			$bean->name = $bean->subject;
			$crmId = $_REQUEST['record'];
			/*if created from crm side */
			if(!empty($crmId)){
				$bean->id = $crmId;
				$field_arr = array('update_attachment_c' , 'custom_attachment_c');
				$GLOBALS['log']->fatal($crmId);
				foreach ($field_arr as $ind => $field){
					$dir_path 	= $_SERVER['DOCUMENT_ROOT']."/upload/multi_".$bean->id."/".$field."/*"; 
					
					$uploaded_files = glob($dir_path);//read temp upload dir for new uploaded files
					$fieldval = '';
					$file_names = [];
					foreach($uploaded_files as $filename){
						if( $filename == '.' || $filename == '..')
							continue;
						$file_names[] = basename($filename);
					}
					$file_names 	= array_unique($file_names);
					$fieldval 		= implode(":",$file_names);
					$bean->$field 	= $fieldval;		
				}
			}
		/*Add Ticket Name in Recently Activity*/
		if(empty($bean->name)){
			$bean->name = $bean->subject;
			
		}
		
		/*Add Total Minuts Logic */
		$bean->total_spent_minuts = $bean->spent_hours + $bean->total_spent_minuts;
		if(!empty($_FILES["case_attachment"]["name"])){			
			$this->upload($_FILES , 'case', $bean);
		}
		if(!empty($_FILES["update_attachment"]["name"])){
			$this->upload($_FILES , 'update', $bean);
		}
	}
	
	private function upload($files , $field , $bean){
		global $sugar_config;
		$utype = explode(".",$files[$field."_attachment"]["name"]);
		$ulegn = sizeof($utype) - 1;
		$utype_low = strtolower($utype[$ulegn]);
		$myarr = ["txt", "doc", "docx", "pdf", "png", "jpeg", "jpg", "img", "ppt", "xls", "xlsx", "csv", "css", "xml", "gif"];
		if (in_array($utype_low, $myarr) && !empty($utype)) 
		  {
			if(!empty($files[$field."_attachment"]["size"]) && $files[$field."_attachment"]["size"] <= $sugar_config['upload_maxsize']){
				
				$target_dir = "upload/".$field."_attachement/";
				$target_file = $target_dir . $bean->id;
				if (file_exists($target_file)) {
					unlink($target_file);
				}
				if(move_uploaded_file($files[$field."_attachment"]["tmp_name"], $target_file)){
						if($field == 'case')
							$bean->case_attachment = $files[$field."_attachment"]["name"];
						if($field == 'update')
							$bean->update_attachment = $files[$field."_attachment"]["name"];
				}
			}
		  }else{			
				//SugarApplication::appendErrorMessage("Update Attachement ISN'T Uploaded");
			}
		
	}
	
}


?>