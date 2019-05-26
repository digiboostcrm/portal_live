<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/UT_RightSignature/UT_RightSignature_sugar.php');
class UT_RightSignature extends UT_RightSignature_sugar {
	
	function __construct(){
		parent::__construct();
	}
    
    function save($check_notify = FALSE){
        global $sugar_config;

        if (empty($this->id)  || $this->new_with_id){
            
            if($sugar_config['dbconfig']['db_type'] == 'mssql'){
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as INT))+1 FROM ut_rightsignature");
            } else {
                $this->number = $this->db->getOne("SELECT MAX(CAST(number as UNSIGNED))+1 FROM ut_rightsignature");
            }
            if(empty($this->number)){
                $this->number=1;
            }
        }
        $this->document_name = 'RS : '.$this->number;
        parent::save($check_notify);
    }
	
}
?>