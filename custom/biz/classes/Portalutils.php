<?php

/**
 * The file used to manage actions for validating license 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once('modules/Administration/Administration.php');

class Portalutils {

    /**
     * Description :: This function is used to check validation status of license for Wordpress Customer Portal.
     * 
     * @param $licensekey - licence key to validate
     * @return bool      0 - license not validated
     *                   1 - license validated
     *         string    $result - license status message
     */

    public static function checkPluginLicense()
    {
        // get validate license status

        if (empty($_REQUEST['sub_method'])) {
            header('HTTP/1.1 400 Bad Request');
            $response = "method is required.";
            $json = getJSONobj();
            echo $json->encode($response);
            }


//load license validation config
        global $currentModule;
        require_once('custom/modules/' . $currentModule . '/license/OutfittersLicense.php');

        if ($_REQUEST['sub_method'] == 'validate') {
            OutfittersLicense::validate();
        } else if ($_REQUEST['sub_method'] == 'change') {
            OutfittersLicense::change();
        } else if ($_REQUEST['sub_method'] == 'add') {
            OutfittersLicense::add();
        } else if ($_REQUEST['method'] == 'test') {
            //optional param: user_id - test if a specific user has access to the add-on
            //Sugar 6: /index.php?module=bc_portal_layout&action=outfitterscontroller&method=test&to_pdf=1
            //Sugar 7: #bwc/index.php?module=bc_portal_layout&action=outfitterscontroller&method=test&to_pdf=1

            $user_id = null;
            if (!empty($_REQUEST['user_id'])) {
                $user_id = $_REQUEST['user_id'];
            }
            $validate_license = OutfittersLicense::isValid($currentModule, $user_id, true);

            if ($validate_license !== true) {

                echo "License did NOT validate.<br/><br/>Reason: " . $validate_license;


                $validated = OutfittersLicense::doValidate($currentModule);

                if (is_array($validated['result'])) {
                    echo "<br/><br/>Key validation = " . !empty($validated['result']['validated']);
                    require('custom/modules/' . $currentModule . '/license/config.php');

                    if ($outfitters_config['validate_users'] == true) {
                        echo "<br/>User validation = " . !empty($validated['result']['validated_users']);
                        echo "<br/>Licensed User Count = " . $validated['result']['licensed_user_count'];
                        echo "<br/>Current User Count = " . $validated['result']['user_count'];

                        if ($validated['result']['user_count'] > $validated['result']['licensed_user_count']) {
                            echo "<br/><br/>Additional Users Required = " . ($validated['result']['user_count'] - $validated['result']['licensed_user_count']);
                        }
                    }
                }
        } else {
                echo "License validated";
            }
        }
    }


    /**
     * Description :: This function is used to validate Wordpress Customer Portal License.
     * 
     * @param $licencekey - licence key to validate 
     * @return array $response      success - false - license not validated
     *                                        true - license validated
     *                              message - license status message
     */
    public static function validateLicenceSubscription() {
        require_once('modules/Administration/Administration.php');
    $administrationObj = new Administration();
    $administrationObj->retrieveSettings('PortalPlugin');
    $LastValidation = $administrationObj->settings['PortalPlugin_LastValidation'];
    $LastValidationMsg = $administrationObj->settings['PortalPlugin_LastValidationMsg'];
        $response = array();
    if ($LastValidation == 1) {
                        $response['success'] = true;
        $response['message'] = html_entity_decode($LastValidationMsg);
                    } else {
                        $response['success'] = false;
        $response['message'] = html_entity_decode($LastValidationMsg);
                    }
        return $response;
    }
}
