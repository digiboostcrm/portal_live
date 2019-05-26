<?php

/**
 * The file used to view List view for User Group module. 
 * View is displayed if license validated successfully
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once('include/MVC/View/views/view.list.php');

class bc_user_groupViewList extends ViewList {

    function preDisplay() {
        parent::preDisplay();
        $this->lv->export = false;
        $this->lv->delete = false;
        $this->lv->select = false;
    }

    public function display() {
        require_once 'custom/biz/classes/Portalutils.php';
        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        echo '<script type="text/javascript" src="modules/bc_user_group/js/restrict_delete_default_group.js"></script>';
        
        if (!$checkLicenceSubscription['success']) {
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
            }

            parent::display();
        }
    }

}
