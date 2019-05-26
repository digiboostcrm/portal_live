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
require_once('modules/UT_RightSignature/UT_RightSignature.php');

class UT_RightSignatureDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/UT_RightSignature/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'UT_RightSignature');

        $this->searchFields = $dashletData['UT_RightSignatureDashlet']['searchFields'];
        $this->columns = $dashletData['UT_RightSignatureDashlet']['columns'];

        $this->seedBean = new UT_RightSignature();        
    }
}