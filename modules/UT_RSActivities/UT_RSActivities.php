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
require_once('modules/UT_RSActivities/UT_RSActivities_sugar.php');
class UT_RSActivities extends UT_RSActivities_sugar {
	
	function __construct(){
		parent::__construct();
	}
    function get_list_view_data($filter_fields = array()) {
        global $db, $app_list_strings, $mod_strings, $current_language;
        $temp_array = parent::get_list_view_data();

        $aModString_RSActivities = return_module_language($current_language,'UT_RSActivities');
        $sUTRSSql = "SELECT ut_rightsignature.id, ut_rightsignature.document_name, ut_rsactivities.id as rsact_id, ut_rsactivities.name as rs_name, ut_rsactivities.rs_keyword, ut_rsactivities.recipient_name, ut_rsactivities.recipient_email 
                    FROM ut_rightsignature 
                    INNER JOIN ut_rightsignature_ut_rsactivities_c rel ON rel.ut_rightsignature_ut_rsactivitiesut_rightsignature_ida = ut_rightsignature.id 
                    INNER JOIN ut_rsactivities ON ut_rsactivities.id=rel.ut_rightsignature_ut_rsactivitiesut_rsactivities_idb AND ut_rsactivities.deleted=0 AND ut_rsactivities.id='".$temp_array['ID']."'
                    WHERE ut_rightsignature.deleted=0";
		$sUTRSSql = $db->limitQuery($sUTRSSql, 0,1,false,'',false);
        $oRes = $db->query($sUTRSSql,true);
        $aRow = $db->fetchByAssoc($oRes);
        $sRSName = $sRSId = $sRSActi_RecipientName = "";
        if(!empty($aRow['id']))
        {
            $sRSName = $aRow['document_name'];
            $sRSId = $aRow['id'];
            $sRSActId = $aRow['rsact_id'];
            $sRSActi_RecipientName = $aRow['rs_name'];
            $sActivitiesName = '';
            $sStyleFront=" style='padding:0px 10px; background-color:grey; color:white;'";
            $sStyleBack=" style='padding:0px 10px; background-color:green; color:white;'";
            $sActivitiesName = "<span ".$sStyleFront."><a href='index.php?module=UT_RightSignature&action=DetailView&record=".$sRSId."' style='color:white;'>".$aModString_RSActivities['LBL_DOC_PACKET']." ".$sRSName."</a></span> <span ".$sStyleBack."><a href='index.php?module=UT_RSActivities&action=DetailView&record=".$sRSActId."' style='color:white;'>".$sRSActi_RecipientName."</a></span>";
            
            $temp_array['RS_SUMMARY'] = $sActivitiesName;
        }
        return $temp_array;
    }
}
?>