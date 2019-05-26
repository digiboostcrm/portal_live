<?php


class OutfittersLicense
{
    /**
     * For validation via server-side. Whomever calls this should handle what to do in case of a failure.
     *
     * Check return for "!== true" as the error will be returned in the case that it didn't validate.
     *
     * returns true or else the error string
     */
    public static function isValid($thisModule = null)
    {
        global  $current_user, $sugar_config;

        if (empty($thisModule)) {
            global $currentModule;
            $thisModule = $currentModule;

            //if still empty...then get out of here...in an odd spot in SugarCRM
            if (empty($thisModule)) {
                return true;
            }
        }

        //load license validation config
        require 'modules/'.$thisModule.'/license/config.php';

        $user_id = $current_user->id;
        //check to see if the passed user is allowed to use the add-on
        //if not then return a message...otherwise continue with the normal license check
        //ignore a passed user id if manage_licensed_users is not enabled
        if ((strlen($sugar_config['outfitters_licenses'][$outfitters_config['shortname']]) == 35) && !empty($user_id) && $outfitters_config['validate_users'] == true) {
            global $db;
            $result = $db->query("SELECT id FROM so_users WHERE shortname = '".$db->quote($outfitters_config['shortname'])."' and user_id = '".$db->quote($user_id)."'", false);
            $row = $db->fetchByAssoc($result);
            if (empty($row)) {
                return 'The user does not have access to this add-on.';
            }
        }

        //check last validation
        require_once 'modules/Administration/Administration.php';
        $administration = new Administration();
        $administration->retrieveSettings();
        $last_validation = $administration->settings['SugarOutfitters_'.$outfitters_config['shortname']];
        $trimmed_last = trim($last_validation); //to be safe...
        //make sure serialized string is not empty
        if (!empty($trimmed_last)) {
            $last_validation = base64_decode($last_validation);
            $last_validation = unserialize($last_validation);

            //if enough time hasn't passed then just return the last result
            //even if the last result failed
            $frequency = $outfitters_config['validation_frequency'];
            $elapsed = (7 * 24 * 60 * 60); //default to weekly
            if ($frequency == 'hourly') {
                $elapsed = (60 * 60);
            } elseif ($frequency == 'daily') {
                $elapsed = (24 * 60 * 60);
            }

            if (($last_validation['last_ran'] + $elapsed) >= time()) {
                if ($last_validation['last_result']['success'] === false) {
                    return $last_validation['last_result']['result'];
                } else {
                    return true;
                }
            }
        }
        //otherwise continue with validation

        $validated = self::doValidate($thisModule);

        $store = array(
            'last_ran' => time(),
            'last_result' => $validated,
        );

        $serialized = base64_encode(serialize($store));
        $administration->saveSetting('SugarOutfitters', $outfitters_config['shortname'], $serialized);

        if ($validated['success'] === false) {
            return $validated['result'];
        } else {
            return true;
        }
    }

    /**
     * For validation via client-side (used by License Configuration form).
     *
     * Does NOT obey the validation_frequency setting. Validates every time.
     * This function is meant to be used only on the License Configuration screen for a specific add-on
     */
    public static function validate()
    {
        $json = getJSONobj();
        if (empty($_REQUEST['key'])) {
            header('HTTP/1.1 400 Bad Request');
            $response = 'Key is required.';
            echo $json->encode($response);
        }

        global $sugar_config, $currentModule;

        //load license validation config
        require 'modules/'.$currentModule.'/license/config.php';

        $validated = self::doValidate($currentModule, $_REQUEST['key']);

        $store = array(
            'last_ran' => time(),
            'last_result' => $validated,
        );

        require_once 'modules/Administration/Administration.php';
        $administration = new Administration();
        $serialized = base64_encode(serialize($store));
        $administration->saveSetting('SugarOutfitters', $outfitters_config['shortname'], $serialized);

        if ($validated['success'] === false) {
            header('HTTP/1.1 400 Bad Request');
        } else {
            //use config_override.php...config.php has a higher chance of having rights restricted on servers
            global $currentModule;

            //load license validation config
            require 'modules/'.$currentModule.'/license/config.php';

            require 'modules/Configurator/Configurator.php';
            $cfg = new Configurator();
            $cfg->config['outfitters_licenses'][$outfitters_config['shortname']] = $_REQUEST['key'];
            $cfg->handleOverride();
        }

        echo $json->encode($validated['result']);
    }

    /**
     * Get curl validate response from API
     * returns array(
     *      response => true/false
     *      info    => result set returned by the server.
     */
    public static function curlValidate($url, $post_fields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'/key/validate?'.$post_fields);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $response_array = array(
            'response' => $response,
            'info' => $info,
        );

        return $response_array;
    }

    /**
     * Get curl change response from API
     * returns array(
     *      response => true/false
     *      info    => result set returned by the server.
     */
    public static function curlChange($url, $post_fields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'/key/change');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $response_array = array(
            'response' => $response,
            'info' => $info,
        );

        return $response_array;
    }

    /**
     * Internal method that makes the actual API request.
     *
     * returns array(
     *      success => true/false
     *      result    => result set returned by the server
     */
    public static function doValidate($thisModule, $key = null)
    {
        global $sugar_config;

        //load license validation config
        require 'modules/'.$thisModule.'/license/config.php';

        //if no key is provided then look for an existing key
        if (empty($key)) {
            $key = $sugar_config['outfitters_licenses'][$outfitters_config['shortname']];
            if (empty($key)) {
                return array(
                        'success' => false,
                        'result' => 'Key could not be found locally. Please go to the license configuration tool and enter your key.',
                    );
            }
        }

        $post_fields = 'public_key='.$outfitters_config['public_key'].'&key='.$key;

        if (isset($outfitters_config['validate_users']) && $outfitters_config['validate_users'] == true) {
            $active_users = get_user_array(false, 'Active', '', false, '', ' AND portal_only=0 AND is_group=0');
            $post_fields .= '&user_count='.count($active_users);
        }

        $url = $outfitters_config['api_url'];

        if (strlen($key) == 35) {
            $url = $outfitters_config['api_url_rls'];
        }

        $curlValidate = self::curlValidate($url, $post_fields);

        $json = getJSONobj();
        $result = $json->decode($curlValidate['response']);

        //if it is not a 200 response assume a 400. Good enough for this purpose.
        if ($curlValidate['info']['http_code'] != 200) {
            $url = $outfitters_config['suite_api_url'];
            $curlValidate = self::curlValidate($url, $post_fields);

            $json = getJSONobj();
            $result = $json->decode($curlValidate['response']);

            if ($curlValidate['info']['http_code'] != 200) {
                $GLOBALS['log']->fatal('Unable to validate: '.print_r($result, true));

                return array(
                      'success' => false,
                      'result' => $result,
                  );
            } else {
                return array(
                        'success' => true,
                        'result' => $result,
                    );
            }
        } else {
            return array(
                    'success' => true,
                    'result' => $result,
                );
        }
    }

    /**
     * Only meant to be ran from the scope of the main module. Uses $currentModule.
     */
    public static function change()
    {
        if (empty($_REQUEST['key'])) {
            header('HTTP/1.1 400 Bad Request');
            $response = 'Key is required.';
            $json = getJSONobj();
            echo $json->encode($response);
        }
        if (empty($_REQUEST['user_count'])) {
            header('HTTP/1.1 400 Bad Request');
            $response = 'User count is required.';
            $json = getJSONobj();
            echo $json->encode($response);
        }

        global $currentModule;

        //load license validation config
        require 'modules/'.$currentModule.'/license/config.php';

        $post_fields = 'public_key='.$outfitters_config['public_key'].'&key='.$_REQUEST['key'].'&user_count='.$_REQUEST['user_count'];

        $url = $outfitters_config['api_url'];

        $curlChange = self::curlChange($url, $post_fields);

        //if it is not a 200 response assume a 400. Good enough for this purpose.
        if ($curlChange['info']['http_code'] != 200) {
            $url = $outfitters_config['suite_api_url'];
            $curlChange = self::curlChange($url, $post_fields);

            if ($curlChange['info']['http_code'] != 200) {
                header('HTTP/1.1 400 Bad Request');
                $GLOBALS['log']->fatal('Unable to update the user count: '.print_r($result, true));
            } else {
                require_once 'modules/Administration/Administration.php';
                global $sugar_config, $sugar_version;
                $sugar_config['outfitters_licenses'][$outfitters_config['shortname']] = $_REQUEST['key'];
                rebuildConfigFile($sugar_config, $sugar_version);
            }
        } else {
            require_once 'modules/Administration/Administration.php';
            global $sugar_config, $sugar_version;
            $sugar_config['outfitters_licenses'][$outfitters_config['shortname']] = $_REQUEST['key'];
            rebuildConfigFile($sugar_config, $sugar_version);
        }

        echo $curlChange['response'];
    }

    public static function loadLicenseStrings()
    {
        global $sugar_config, $currentModule, $current_language;

        //load license config file....if it isn't broken don't fix it
        $default_language = $sugar_config['default_language'];

        $langs = array();
        if ($current_language != 'en_us') {
            $langs[] = 'en_us';
        }
        if ($default_language != 'en_us' && $current_language != $default_language) {
            $langs[] = $default_language;
        }
        $langs[] = $current_language;

        foreach ($langs as $lang) {
            $license_strings = array();
            @include_once "modules/rls_Reports/license/language/$lang.lang.php";
            $license_strings_array[] = $license_strings;
        }

        $license_strings = array();
        foreach ($license_strings_array as $license_strings_item) {
            $license_strings = sugarArrayMerge($license_strings, $license_strings_item);
        }

        return $license_strings;
    }

    /**
     * Only meant to be ran from the scope of the main module. Uses $currentModule.
     */
    public static function add()
    {
        if (empty($_REQUEST['licensed_users']) || count($_REQUEST['licensed_users']) == 0) {
            header('HTTP/1.1 400 Bad Request');
            $response = 'No additional licenses were set to be added.';
            $json = getJSONobj();
            echo $json->encode($response);
            exit;
        }

        global $currentModule;

        //load license validation config
        require 'modules/'.$currentModule.'/license/config.php';

        //check to ensure that the licensed_users does not exceed the amount purchased
        $response = self::doValidate($currentModule);

        if (empty($response['success']) || $response['success'] !== true || empty($response['result']['validated'])) {
            header('HTTP/1.1 400 Bad Request');
            $response = 'The license key could not validate. Please check the key and re-validate.';
            $json = getJSONobj();
            echo $json->encode($response);
            exit;
        }

        if ($outfitters_config['validate_users'] == true) {
            if (!empty($response['result']) && (empty($response['result']['validated_users']) || $response['result']['validated_users'] !== true)) {
                header('HTTP/1.1 400 Bad Request');
                $response = 'Insuffient number of user licenses. Please add additional user licenses and try again.';
                $json = getJSONobj();
                echo $json->encode($response);
                exit;
            }
        }

        $fieldDefs = array(
            'id' => array(
                'name' => 'id',
                'vname' => 'LBL_ID',
                'type' => 'id',
                'required' => true,
                'reportable' => true,
            ),
            'deleted' => array(
                'name' => 'deleted',
                'vname' => 'LBL_DELETED',
                'type' => 'bool',
                'default' => '0',
                'reportable' => false,
                'comment' => 'Record deletion indicator',
            ),
            'shortname' => array(
                'name' => 'shortname',
                'vname' => 'LBL_SHORTNAME',
                'type' => 'varchar',
                'len' => 255,
            ),
            'user_id' => array(
                'name' => 'user_id',
                'rname' => 'user_name',
                'module' => 'Users',
                'id_name' => 'user_id',
                'vname' => 'LBL_USER_ID',
                'type' => 'relate',
                'isnull' => 'false',
                'dbType' => 'id',
                'reportable' => true,
                'massupdate' => false,
            ),
        );

        global $db;
        //drop existing
        $sql = "DELETE FROM so_users WHERE shortname = '".$db->quote($outfitters_config['shortname'])."'";
        $db->query($sql, true, 'Unable to reset licensed users for '.$outfitters_config['shortname']);
        foreach ($_REQUEST['licensed_users'] as $licensed_user) {
            $data = array(
                'id' => create_guid(),
                'shortname' => $outfitters_config['shortname'],
                'user_id' => $licensed_user,
                'deleted' => 0,
            );
            $db->insertParams('so_users', $fieldDefs, $data);
        }

        $response = array(
            'success' => true,
        );

        $json = getJSONobj();
        echo $json->encode($response);
        exit;
    }
}
