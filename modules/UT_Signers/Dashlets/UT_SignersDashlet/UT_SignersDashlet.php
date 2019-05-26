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
require_once('modules/UT_Signers/UT_Signers.php');

class UT_SignersDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/UT_Signers/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'UT_Signers');

        $this->searchFields = $dashletData['UT_SignersDashlet']['searchFields'];
        $this->columns = $dashletData['UT_SignersDashlet']['columns'];

        $this->seedBean = new UT_Signers();        
    }
}