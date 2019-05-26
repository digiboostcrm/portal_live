<?php

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldMultiupload extends SugarFieldBase {
	
	function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false) {
		$displayParams['bean_id'] 	= 'id';
		$record 					= $_REQUEST['record'];
		$files_path 				= $_SERVER['DOCUMENT_ROOT'].'/upload/multi_'; 
		
		$GLOBALS['log']->debug("Edit File Path: ".$files_path);		
		
		$this->ss->assign('PATH', $files_path);
		$this->ss->assign('FIELD_NAME', $vardef['name']);
		
		$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
		return $this->fetch($this->findTemplate('EditView'));
	}
	function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false) {
		$displayParams['bean_id'] 	= 'id';
		$record 					= $_REQUEST['record'];
		$this->ss->assign('MULTI_UPLOAD', true);
		require_once('modules/Multiupload/license/MultiuploadOFLicense.php');
		
		$validate_license = MultiuploadOFLicense::isValid('Multiupload');
		
		if($validate_license !== true) {
			$this->ss->assign('MULTI_UPLOAD', false);
		}
		
		$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
		return $this->fetch($this->findTemplate('DetailView'));
	}
	function getUserEditView($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false) {
		$displayParams['bean_id']='id';
		$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
		return $this->fetch($this->findTemplate('UserEditView'));
	}
	function getUserDetailView($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false) {
		$displayParams['bean_id']='id';
		$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
		return $this->fetch($this->findTemplate('UserDetailView'));
	}
	
	function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'SearchView');
	}
	
	/* Copy files from temp placeholder to record folder 
	*/
	public function save(&$bean, $params, $field, $properties, $prefix = '') {
		/*
		if(!empty($bean->id)) 
		{ 
			$record_id 	= $bean->id;	
			$dir_path 	= $_SERVER['DOCUMENT_ROOT']."/upload/multi_".$record_id."/".$field."/*"; 
			
			$GLOBALS['log']->debug("Uploaded Path : ".$dir_path);
			
			$uploaded_files = glob($dir_path);//read temp upload dir for new uploaded files
			foreach($uploaded_files as $filename){
				if( $filename == '.' || $filename == '..')
					continue;
				$file_names[] = basename($filename);
			}
			
			$file_names 	= array_unique($file_names);
			$fieldval 		= implode(":",$file_names);
			
			$GLOBALS['log']->debug("Field Value >".$fieldval);
			
			$bean->$field 	= $fieldval;		
		}
		*/
	}
}
?>