<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
require_once('include/MVC/View/views/view.detail.php');

class UT_RightSignatureViewDetail extends ViewDetail {


 	function __construct(){
 		parent::__construct();
 	}

 	function display(){

		if(empty($this->bean->id)){
			global $app_strings;
			sugar_die($app_strings['ERROR_NO_RECORD']);
		}

		require_once('modules/AOS_PDF_Templates/formLetter.php');
		formLetter::DVPopupHtml('Accounts');

		$this->dv->process();
		global $mod_strings, $app_list_strings;
        $sThumbNailURL = '';
        if(!empty($this->bean->thumbnail_url))
            $sThumbNailURL = '<a href="'.$this->bean->thumbnail_url.'"><img src="'.$this->bean->thumbnail_url.'" /></a>';
		$this->ss->assign("sThumbNailURL", $sThumbNailURL);

        if(empty($this->bean->id)){
			global $app_strings;
			sugar_die($app_strings['ERROR_NO_RECORD']);
		}
        
        if($this->bean->state == "pending"){
            $sStyle=" style='padding:0px 10px; background-color:orange; color:white;'";
        }
        else if($this->bean->state == "sent"){
            $sStyle=" style='padding:0px 10px; background-color:lightseagreen; color:white;'";
        }
        else if($this->bean->state == "signed"){
            $sStyle=" style='padding:0px 10px; background-color:green; color:white;'";
        }
        else if($this->bean->state == "executed"){
            $sStyle=" style='padding:0px 10px; background-color:green; color:white;'";
        }
        else
            $sStyle="";
        $sStateHTML = "<span ".$sStyle.">".$app_list_strings['rs_state_list'][$this->bean->state]."</span>";

$js_script=<<<EOQ
<script>
    $(document).ready(function(){
        $('#original_url').css('word-wrap','break-word');
        $('#pdf_url').css('word-wrap','break-word');
        $('#thumbnail_url').css('word-wrap','break-word');
        $('#signed_pdf_url').css('word-wrap','break-word');
    });
</script>
EOQ;
        $this->ss->assign('sStateHTML', $sStateHTML);
		echo $this->dv->display();
        echo $js_script;
 	}
}
?>