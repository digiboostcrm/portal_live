<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/UT_RSActivities/UT_RSActivities.php');

class UT_RSActivitiesDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/UT_RSActivities/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'UT_RSActivities');

        $this->searchFields = $dashletData['UT_RSActivitiesDashlet']['searchFields'];
        $this->columns = $dashletData['UT_RSActivitiesDashlet']['columns'];

        $this->seedBean = new UT_RSActivities();        
    }
}