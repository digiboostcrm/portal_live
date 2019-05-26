<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
class RSLogicHooksCls {
    
    public function colourFields($bean, $event, $arguments) { 
        global $app_list_strings;
        if($event == 'process_record' && isset($_REQUEST['method']) && $_REQUEST['method'] =='get_entry_list')
            return;
        if($bean->state == "pending"){
            $sStyle=" style='padding:0px 10px; background-color:orange; color:white;'";
        }
        else if($bean->state == "sent"){
            $sStyle=" style='padding:0px 10px; background-color:lightseagreen; color:white;'";
        }
        else if($bean->state == "signed"){
            $sStyle=" style='padding:0px 10px; background-color:green; color:white;'";
        }
        else if($bean->state == "executed"){
            $sStyle=" style='padding:0px 10px; background-color:green; color:white;'";
        }
        else
            $sStyle="";
        $bean->state = "<span ".$sStyle.">".$app_list_strings['rs_state_list'][$bean->state]."</span>";
    }
}