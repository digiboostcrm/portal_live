<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
/**
 * The file used to store REST API functions. 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once('service/v4/SugarWebServiceImplv4.php');
require_once('service/v4_1/SugarWebServiceUtilv4_1.php');
require_once('custom/service/v4_1_custom/SugarWebServiceUtilv4_1_custom.php');

class SugarWebServiceImplv4_1_custom extends SugarWebServiceImplv4 {

    /**
     * Class Constructor Object
     *
     */
    public function __construct() {
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
    }

    function IsActivate() {

        require_once('modules/Administration/Administration.php');
        require_once 'custom/biz/classes/Portalutils.php';

        global $app_strings;
        $oAdministration = new Administration();
        $oAdministration->retrieveSettings('PortalPlugin');
        $forntend_framwork = $oAdministration->settings['PortalPlugin_PortalInstance'];
        
        $checkResult = Portalutils::validateLicenceSubscription();
        if ($checkResult['success']) {
                    return array('success' => 1,'fronend_framework'=>$forntend_framwork);
                } else {
                return array('success' => 0, 'error' => '201', 'message' => $app_strings['VALIDATE_FAIL'],'fronend_framework'=>$forntend_framwork);
        }
    }

function getChatConfiguration() {
        global $app_strings;
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('PortalPlugin');
        $chatEnable = $administrationObj->settings['PortalPlugin_ChatEnable'];
        $talkApikey = $administrationObj->settings['PortalPlugin_TalkApikey'];
        $resultOfChatConfig = array();
        $resultOfChatConfig['TalkApiKey'] = $talkApikey;
        $resultOfChatConfig['ChatEnable'] = $chatEnable;

        return $resultOfChatConfig;
    }

    /**
     * Function : get_customListlayout
     * get custom portal layout from the CRM
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $module_name -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
     * @param String $view -- The view name to get layout of that view
     * 
     * @return Array 'layout_fields' -- Array - The records that were retrieved
     *              
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function get_customListlayout($session, $module_name, $view) {
        global $db, $beanList;
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        $error = new SoapError();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: checkEmailAndSendUsername->getCasesRelatedToContact');
            return;
        }
        $module_obj = new $beanList[$module_name]();

        $field_defination = $module_obj->getFieldDefinitions();
        if ($view == 'list') { //listview
            try {

                $get_data_query = "select layout,modified_date from portal_layout where module_name='$module_name' and layout_type='ListView'";
                $get_layout = $db->query($get_data_query);
                while ($row = $db->fetchByassoc($get_layout)) {
                    $layout_data = html_entity_decode($row['layout']);
                    $layout_fields = json_decode($layout_data, true);
                    $modified_date = $row['modified_date'];
                }
                if (isset($layout_fields)) {

                    foreach ($layout_fields as $k => $v) {
                        unset($layout_fields[$k]);
                        $new_key = $v['name'];
                        $layout_fields[$new_key] = $v;
                    }

                    $PortallistViewDefs['panels'] = $layout_fields;
                    $layout_fields = $PortallistViewDefs;

                    foreach ($layout_fields[$module_name] as $field_name => $def) {
                        //check for relate field and provide additional information
                        if ($field_defination[strtolower($field_name)]['type'] == 'relate') {
                            $layout_fields['panels'][$field_name]['related_module'] = $field_defination[strtolower($field_name)]['module'];
                            $layout_fields['panels']['link'] = $field_defination[strtolower($field_name)]['link'];
                        }
                    }
                    $layout_fields['modified_date'] = $modified_date;
                    return $layout_fields;
                } else {
                    //layout is not set for this module
                    throw new Exception("Portal ListView layout is not set for {$module_name}.");
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        if ($view == 'edit') { //editview
            try {
                $get_data_query = "select layout,modified_date from portal_layout where module_name='$module_name' and layout_type='EditView'";
                $get_layout = $db->query($get_data_query);
                while ($row = $db->fetchByassoc($get_layout)) {
                    $layout_data = html_entity_decode($row['layout']);
                    $layout_fields = json_decode($layout_data, true);
                    $layout_fields['modified_date'] = $row['modified_date'];
                }
                if (isset($layout_fields)) {

                    $PortallistViewDefs = $layout_fields;
                    $layout_fields = $PortallistViewDefs;


                    foreach ($layout_fields['panels'] as $panelID => $panelRows) {
                        $targetModuleLang = return_module_language('en_us', $module_name);


                        $lableValue = (empty($targetModuleLang[$panelID]) || is_null($targetModuleLang[$panelID])) ? translate($panelID, 'Administration') : $targetModuleLang[$panelID];
                        if (empty($lableValue)) {
                            $targetAppLang = return_application_language('en_us');
                            $lableValue = $targetAppLang[$panelID];
                        }
                        foreach ($panelRows as $row_index => $panelRow) {
                            foreach ($panelRow as $column_index => $column) {
                                if ($column['type'] == 'relate') {
                                    //check for relate field and provide additional information
                                    $panelRows[$row_index][$column_index]['relate_module'] = $field_defination[$column['name']]['module'];
                                    $panelRows[$row_index][$column_index]['link'] = $field_defination[$column['name']]['link'];
                                }
                            }
                        }

                        $layout_fields['panels'][$panelID] = array('lable_value' => $lableValue, 'rows' => $panelRows);
                    }
                    return $layout_fields;
                } else {
                    //layout is not set for this module
                    throw new Exception("Portal EditView layout is not set for {$module_name}.");
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        if ($view == 'detail') { //detailview
            try {
                $get_data_query = "select layout,modified_date from portal_layout where module_name='$module_name' and layout_type='DetailView'";
                $get_layout = $db->query($get_data_query);
                while ($row = $db->fetchByassoc($get_layout)) {
                    $layout_data = html_entity_decode($row['layout']);
                    $layout_fields = json_decode($layout_data, true);
                    $layout_fields['modified_date'] = $row['modified_date'];
                }
                if (isset($layout_fields)) {

                    $PortallistViewDefs = $layout_fields;
                    $layout_fields = $PortallistViewDefs;

                    foreach ($layout_fields['panels'] as $panelID => $panelRows) {
                        $targetModuleLang = return_module_language('en_us', $module_name);


                        $lableValue = (empty($targetModuleLang[$panelID]) || is_null($targetModuleLang[$panelID])) ? translate($panelID, 'Administration') : $targetModuleLang[$panelID];
                        if (empty($lableValue)) {
                            $targetAppLang = return_application_language('en_us');
                            $lableValue = $targetAppLang[$panelID];
                        }

                        foreach ($panelRows as $row_index => $panelRow) {
                            foreach ($panelRow as $column_index => $column) {
                                if ($column['type'] == 'relate') {
                                    //check for relate field and provide additional information
                                    $panelRows[$row_index][$column_index]['relate_module'] = $field_defination[$column['name']]['module'];
                                    $panelRows[$row_index][$column_index]['link'] = $field_defination[$column['name']]['link'];
                                }
                            }
                        }

                        $layout_fields['panels'][$panelID] = array('lable_value' => $lableValue, 'rows' => $panelRows);
                    }
                    return $layout_fields;
                } else {
                    // layout is not set for this module
                    throw new Exception("Portal DetailView layout is not set for {$module_name}.");
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * Function : store_wpkey
     * store Portal url setting
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $key -- WP Key to store
     * 
     * @return string -- "success or failure msg"
     *              
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function store_wpkey($session, $key) {
        global $db;
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        $error = new SoapError();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'Contacts', 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: checkEmailAndSendUsername->getCasesRelatedToContact');
            return;
        }
        // store Customer portal url setting
        if (trim($key) != '') {
            $category = "wpkey";
            $name = "wpkey";
            $get_existing_record = "SELECT * FROM config WHERE category = '{$category}'";
            $result = $db->query($get_existing_record);

            if ($result->num_rows > 0) {
                $query = "UPDATE config
                            SET value= '{$key}'
                          WHERE category = '{$category}'";
                $GLOBALS['log']->fatal('result is' . print_r($query, true));
            } else {
                $query = "INSERT INTO config(category,name,value)
                      VALUES ('{$category}','{$name}','{$key}')";
            }

            try {
                $result = $db->query($query);
                if ($result == false) {
                    throw new Exception("{$key} is not inserted in config.");
                } else {
                    throw new Exception("{$key} is inserted in config successfully.");
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * Function : getUser_accessibleModules
     *  get user accessible modules as per role assigned
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $module_name -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
     * @param String $user_id -- User id to get role of user
     * 
     * @return array '$access_right_modules' -- 'Array' modules list array which are accessible by given user
     *              
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function getUser_accessibleModules($session, $module_name, $user_id) {
        // get user accessible modules as per role assigned
        $error = new SoapError();
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_modified_relationships');
            return;
        } // if
        global $db;
        $module_array = array('Accounts', 'Contacts', 'Leads', 'Opportunities', 'Documents', 'Calls', 'Meetings', 'Tasks', 'Notes', 'Cases');

        $role = ACLRole::getUserRoles($user_id, false);
        $role_id = $role[0]->id;
        $actions = ACLRole::getRoleActions($role_id);
        foreach ($actions as $module => $action) {
            $access_actions[$module] = $action['module']['access']['id'];
        }
        foreach ($access_actions as $modulename => $access) {
            if (in_array($modulename, $module_array)) {

                $get_override_action = "SELECT * FROM acl_roles_actions
                                    WHERE role_id = '{$role_id}' AND action_id = '{$access}'
                                    AND deleted = 0";

                $result = $db->query($get_override_action);
                $row = $db->fetchByAssoc($result);

                if ($row['access_override'] != -98) {
                    $access_right_modules[] = $modulename;
                }
            }
        }
        return $access_right_modules;
    }

    /**
     * Function : getPortal_accessibleModules
     *  get portal user accessible modules as per user group assigned
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $contact_id -- Contactr id of WP user
     * 
     * @return array '$portal_accessible_modules' -- 'Array' modules list array which are accessible by given contact
     *              
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function getPortal_accessibleModules($session, $contact_id) {

        require_once 'custom/biz/function/default_portal_module.php';

        global $app_list_strings, $sugar_version, $sugar_flavor, $db;
        //get modules as per related user group assigned to the contact
        $error = new SoapError();
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_modified_relationships');
            return;
        } // if
        // get user group accessible modules
        $contacts = new Contact();
        $contacts->retrieve($contact_id);
        $contacts->load_relationship('bc_user_group_contacts');

        $user_accessible_modules = get_modules();
        $actions = array("created", "modify", "deleted");
        $portal_accessible_modules = array();
        foreach ($contacts->bc_user_group_contacts->getBeans() as $group) {
            $portal_accessible_modules = unencodeMultienum($group->accessible_modules);
            $portalAccessLevel = json_decode(html_entity_decode($group->portal_accessiable_module));
            $conatct_group = $group->name;
        }

        //getting modules in key-value structure
        $returnModArray = array();

        $returnModArray = array();
        $actions_arrays = array('create', 'delete', 'edit');

        $fullaccessnonArray = array("AOS_Contracts", "AOS_Invoices", "AOK_KnowledgeBase", "AOS_Quotes", "Calls", "Meetings");
        $editaccessnonArray = array();
        $createaccessnonArray = array();
        $deleteaccessnonArray = array();


        $modulelablesTypeArray = array();

        foreach ($user_accessible_modules as $key => $moduleName) {
            if ($portalAccessLevel == "" || $conatct_group == 'Default') {
                $singluar = isset($app_list_strings['moduleListSingular'][$key]) ? $app_list_strings['moduleListSingular'][$key] : $app_list_strings['moduleList'][$key];
                $modulelablesTypeArray[$key] = array('singluar' => $singluar,
                    'plural' => $app_list_strings['moduleList'][$key]);
                foreach ($actions_arrays as $num => $action_name) {
                    $actions_array[$key]['module_name'] = $app_list_strings['moduleList'][$key];
                    if (in_array($key, $fullaccessnonArray)) {
                        $actions_array[$key]['action'][$action_name] = 'false';
                    } else if (in_array($key, $editaccessnonArray) && $action_name == 'edit') {
                        $actions_array[$key]['action'][$action_name] = 'false';
                    } else if (in_array($key, $createaccessnonArray) && $action_name == 'create') {
                        $actions_array[$key]['action'][$action_name] = 'false';
                    } else if (in_array($key, $deleteaccessnonArray) && $action_name == 'delete') {
                        $actions_array[$key]['action'][$action_name] = 'false';
                    } else if ($conatct_group == 'Default' && $action_name == 'delete') {
                        $actions_array[$key]['action'][$action_name] = 'true';
                    } else if ($action_name == 'delete') {
                        $actions_array[$key]['action'][$action_name] = 'false';
                    } else {
                        $actions_array[$key]['action'][$action_name] = 'true';
                    }
                }
            } else {
                if (array_key_exists($key, $portalAccessLevel)) {
                    foreach ($portalAccessLevel as $mod_key => $mod_action) {
                        if ($key == $mod_key) {
                            $singluar = isset($app_list_strings['moduleListSingular'][$mod_key]) ? $app_list_strings['moduleListSingular'][$mod_key] : $app_list_strings['moduleList'][$mod_key];
                            $modulename = $app_list_strings['moduleList'][$mod_key];
                            $modulelablesTypeArray[$mod_key] = array('singluar' => $singluar,
                                'plural' => $app_list_strings['moduleList'][$mod_key]);
                            foreach ($actions_arrays as $num => $action_name) {
                                $actions_array[$mod_key]['module_name'] = $app_list_strings['moduleList'][$mod_key];
                                if (in_array($action_name, $mod_action)) {
                                    $actions_array[$mod_key]['action'][$action_name] = 'true';
                                } else {
                                    $actions_array[$mod_key]['action'][$action_name] = 'false';
                                }
                            }
                        }
                    }
                }
            }
        }
        $actions_array['Contacts']['action']['edit'] = 'true';
        $views = array('list', 'edit', 'detail');
        $layouts = array();
        //get layout for all accessible modules
        foreach ($actions_array as $moduleName => $moduleActions) {
            //get layout of all view
            foreach ($moduleActions as $key => $value) {
                if ($value['access'] != 'false') {
                    foreach ($views as $view) {
                        $layouts[$moduleName][$view] = $this->get_customListlayout($session, $moduleName, $view);
                    }
                }
            }
        }
        $currency = $this->get_entry_list($session, "Currencies", "status='Active'");

        $obj_currency = new Currency();
        $obj_currency->retrieve('-99');

        $result_array['Currencies'] = array();
        // default currency id is -99
        $result_array['Currencies'][$obj_currency->id] = array();
        $result_array['Currencies'][$obj_currency->id]['name'] = $obj_currency->name;
        $result_array['Currencies'][$obj_currency->id]['symbol'] = $obj_currency->symbol;
        $result_array['Currencies'][$obj_currency->id]['iso4217'] = $obj_currency->iso4217;
        $result_array['Currencies'][$obj_currency->id]['conversion_rate'] = $obj_currency->conversion_rate;
        $res = 0;
        foreach ($currency['entry_list'] as $value) {
            $result_array['Currencies'][$value['id']][$value['name_value_list']['name']['name']] = $value['name_value_list']['name']['value'];
            $result_array['Currencies'][$value['id']][$value['name_value_list']['symbol']['name']] = $value['name_value_list']['symbol']['value'];
            $result_array['Currencies'][$value['id']][$value['name_value_list']['iso4217']['name']] = $value['name_value_list']['iso4217']['value'];
            $result_array['Currencies'][$value['id']][$value['name_value_list']['conversion_rate']['name']] = $value['name_value_list']['conversion_rate']['value'];
            $res++;
        }
        $result_array['contact_group'] = $conatct_group;
        $result_array['accessible_modules'] = $actions_array;
        $result_array['accessible_modulesLablesType'] = $modulelablesTypeArray;
        $result_array['layouts'] = $layouts;
        $result_array['sugar_version'] = $sugar_version;
        $result_array['sugar_flavor'] = $sugar_flavor;

        return $result_array;
    }

    /**
     * Function : getUserTimezone
     *   get user timezone
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $module_name -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
     * @param String $user_id -- User id
     * 
     * @return string '$timezone' -- current timezone of given user
     *              
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function getUserTimezone($session, $module_name, $user_id) {// get user timezone
        $error = new SoapError();
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_modified_relationships');
            return;
        } // if

        $user = new User();
        $user->retrieve($user_id);
        $user->loadPreferences();
        $timezone = $user->getPreference('timezone');
        return $timezone;
    }

    /**
     * Retrieve a collection of beans that are related to the specified bean and optionally return relationship data for those related beans.
     * So in this API you can get contacts info for an account and also return all those contact's email address or an opportunity info also.
     *
     * @param String $session -- Session ID returned by a previous call to login.
     * @param String $module_name -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
     * @param String $module_id -- The ID of the bean in the specified module
     * @param String $link_field_name -- The name of the lnk field to return records from.  This name should be the name the relationship.
     * @param String $related_module_query -- A portion of the where clause of the SQL statement to find the related items.  The SQL query will already be filtered to only include the beans that are related to the specified bean.
     * @param Array $related_fields - Array of related bean fields to be returned.
     * @param Array $related_module_link_name_to_fields_array - For every related bean returrned, specify link fields name to fields info for that bean to be returned. For ex.'link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address'))).
     * @param Number $deleted -- false if deleted records should not be include, true if deleted records should be included.
     * @param String $order_by -- field to order the result sets by
     * @param Number $offset -- where to start in the return
     * @param Number $limit -- number of results to return (defaults to all)
     * @return Array 'entry_list' -- Array - The records that were retrieved
     *               'relationship_list' -- Array - The records link field data. The example is if asked about accounts contacts email address then return data would look like Array ( [0] => Array ( [name] => email_addresses [records] => Array ( [0] => Array ( [0] => Array ( [name] => id [value] => 3fb16797-8d90-0a94-ac12-490b63a6be67 ) [1] => Array ( [name] => email_address [value] => hr.kid.qa@example.com ) [2] => Array ( [name] => opt_out [value] => 0 ) [3] => Array ( [name] => primary_address [value] => 1 ) ) [1] => Array ( [0] => Array ( [name] => id [value] => 403f8da1-214b-6a88-9cef-490b63d43566 ) [1] => Array ( [name] => email_address [value] => kid.hr@example.name ) [2] => Array ( [name] => opt_out [value] => 0 ) [3] => Array ( [name] => primary_address [value] => 0 ) ) ) ) )
     * @exception 'SoapFault' -- The SOAP error, if any
     */
    function get_relationships($session, $module_name, $module_id, $link_field_name, $related_module_query, $related_fields, $related_module_link_name_to_fields_array, $deleted, $order_by = '', $offset = 0, $limit = false) {
        $GLOBALS['log']->info('Begin: SugarWebServiceImpl->get_relationships');
        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        global $beanList, $beanFiles;
        $error = new SoapError();
/*
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
            return;
        } // if
*/
        $mod = BeanFactory::getBean($module_name, $module_id);

        if (!self::$helperObject->checkQuery($error, $related_module_query, $order_by)) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
            return;
        } // if

        if (!self::$helperObject->checkACLAccess($mod, 'DetailView', $error, 'no_access')) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
            return;
        } // if

        $output_list = array();
        $linkoutput_list = array();

        // get all the related modules data.
        $result = self::$helperObject->getRelationshipResults($mod, $link_field_name, $related_fields, $related_module_query, $order_by, $offset, $limit);

        if (self::$helperObject->isLogLevelDebug()) {
            $GLOBALS['log']->debug('SoapHelperWebServices->get_relationships - return data for getRelationshipResults is ' . var_export($result, true));
        } // if
        if ($result) {

            $list = $result['rows'];
            $filterFields = $result['fields_set_on_rows'];

            if (sizeof($list) > 0) {
                // get the related module name and instantiate a bean for that
                $submodulename = $mod->$link_field_name->getRelatedModuleName();
                $submoduletemp = BeanFactory::getBean($submodulename);

                foreach ($list as $row) {
                    $submoduleobject = @clone($submoduletemp);
                    // set all the database data to this object
                    foreach ($filterFields as $field) {
                        $submoduleobject->$field = $row[$field];
                    } // foreach
                    if (isset($row['id'])) {
                        $submoduleobject->id = $row['id'];
                    }
                    $output_list[] = self::$helperObject->get_return_value_for_fields($submoduleobject, $submodulename, $filterFields);
                    if (!empty($related_module_link_name_to_fields_array)) {
                        $linkoutput_list[] = self::$helperObject->get_return_value_for_link_fields($submoduleobject, $submodulename, $related_module_link_name_to_fields_array);
                    } // if
                } // foreach
            }
        } // if

        $GLOBALS['log']->info('End: SugarWebServiceImpl->get_relationships');
        return array('entry_list' => $output_list, 'relationship_list' => $linkoutput_list);
    }

    /**
     * get_modified_relationships
     *
     * Get a list of the relationship records that have a date_modified value set within a specified date range.  This is used to
     * help facilitate sync operations.  The module_name should be "Users" and the related_module one of "Meetings", "Calls" and
     * "Contacts".
     *
     * @param xsd:string $session String of the session id
     * @param xsd:string $module_name String value of the primary module to retrieve relationship against
     * @param xsd:string $related_module String value of the related module to retrieve records off of
     * @param xsd:string $from_date String value in YYYY-MM-DD HH:MM:SS format of date_start range (required)
     * @param xsd:string $to_date String value in YYYY-MM-DD HH:MM:SS format of ending date_start range (required)
     * @param xsd:int $offset Integer value of the offset to begin returning records from
     * @param xsd:int $max_results Integer value of the max_results to return; -99 for unlimited
     * @param xsd:int $deleted Integer value indicating deleted column value search (defaults to 0).  Set to 1 to find deleted records
     * @param xsd:string $module_user_id String value of the user id (optional, but defaults to SOAP session user id anyway)  The module_user_id value
     * here ought to be the user id of the user initiating the SOAP session
     * @param tns:select_fields $select_fields Array value of fields to select and return as name/value pairs
     * @param xsd:string $relationship_name String value of the relationship name to search on
     * @param xsd:string $deletion_date String value in YYYY-MM-DD HH:MM:SS format for filtering on deleted records whose date_modified falls within range
     * this allows deleted records to be returned as well
     *
     * @return Array records that match search criteria
     */
    function get_modified_relationships($session, $module_name, $related_module, $from_date, $to_date, $offset, $max_results, $deleted = 0, $module_user_id = '', $select_fields = array(), $relationship_name = '', $deletion_date = '') {
        global $beanList, $beanFiles, $current_user;
        $error = new SoapError();
        $output_list = array();

        if (empty($from_date)) {
            $error->set_error('invalid_call_error, missing from_date');
            return array('result_count' => 0, 'next_offset' => 0, 'field_list' => $select_fields, 'entry_list' => array(), 'error' => $error->get_soap_array());
        }

        if (empty($to_date)) {
            $error->set_error('invalid_call_error, missing to_date');
            return array('result_count' => 0, 'next_offset' => 0, 'field_list' => $select_fields, 'entry_list' => array(), 'error' => $error->get_soap_array());
        }

        self::$helperObject = new SugarWebServiceUtilv4_1_custom();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', $module_name, 'read', 'no_access', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImpl->get_modified_relationships');
            return;
        } // if

        if (empty($beanList[$module_name]) || empty($beanList[$related_module])) {
            $error->set_error('no_module');
            return array('result_count' => 0, 'next_offset' => 0, 'field_list' => $select_fields, 'entry_list' => array(), 'error' => $error->get_soap_array());
        }

        global $current_user;
        if (!self::$helperObject->check_modules_access($current_user, $module_name, 'read') || !self::$helperObject->check_modules_access($current_user, $related_module, 'read')) {
            $error->set_error('no_access');
            return array('result_count' => 0, 'next_offset' => 0, 'field_list' => $select_fields, 'entry_list' => array(), 'error' => $error->get_soap_array());
        }

        if ($max_results > 0 || $max_results == '-99') {
            global $sugar_config;
            $sugar_config['list_max_entries_per_page'] = $max_results;
        }

        // Cast to integer
        $deleted = (int) $deleted;
        $query = "(m1.date_modified > " . db_convert("'" . $GLOBALS['db']->quote($from_date) . "'", 'datetime') . " AND m1.date_modified <= " . db_convert("'" . $GLOBALS['db']->quote($to_date) . "'", 'datetime') . " AND {0}.deleted = $deleted)";
        if (isset($deletion_date) && !empty($deletion_date)) {
            $query .= " OR ({0}.date_modified > " . db_convert("'" . $GLOBALS['db']->quote($deletion_date) . "'", 'datetime') . " AND {0}.date_modified <= " . db_convert("'" . $GLOBALS['db']->quote($to_date) . "'", 'datetime') . " AND {0}.deleted = 1)";
        }

        if (!empty($current_user->id)) {
            $query .= " AND m2.id = '" . $GLOBALS['db']->quote($current_user->id) . "'";
        }

        //if($related_module == 'Meetings' || $related_module == 'Calls' || $related_module = 'Contacts'){
        $query = string_format($query, array('m1'));
        //}

        require_once('soap/SoapRelationshipHelper.php');
        $results = retrieve_modified_relationships($module_name, $related_module, $query, $deleted, $offset, $max_results, $select_fields, $relationship_name);

        $list = $results['result'];

        foreach ($list as $value) {
            $output_list[] = self::$helperObject->array_get_return_value($value, $results['table_name']);
        }

        $next_offset = $offset + count($output_list);

        return array(
            'result_count' => count($output_list),
            'next_offset' => $next_offset,
            'entry_list' => $output_list,
            'error' => $error->get_soap_array()
        );
    }

// fn
    // retrive  Transaction 
    function get_transaction_feilds($session) {
        $GLOBALS['log']->info('Begin: SugarWebServiceImplv4_1_custom->get_transaction_feilds');
        $error = new SoapError();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
        $oAdministration = new Administration();
        $oAdministration->retrieveSettings('payment');
        $payment_amount_module = $oAdministration->settings['payment_amount_module'];
        $payment_amount_fields = $oAdministration->settings['payment_amount_feilds'];
        $payment_payment_module = $oAdministration->settings['payment_payment_module'];
        $payment_payment_feilds = $oAdministration->settings['payment_payment_feilds'];
        $payment_status = $oAdministration->settings['payment_status'];
        //  $GLOBALS['log']->fatal("typee " . print_r($t, 1));


        return array('payment_status' => $payment_status, 'payment_amount_module' => $payment_amount_module, 'payment_amount_fields' => $payment_amount_fields, 'payment_payment_module' => $payment_payment_module, 'payment_payment_feilds' => $payment_payment_feilds);
    }

// For retrive all quotes's list with it's relationship data

    function get_entry_list_quotes($session, $module_name) {
        $error = new SoapError();
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
        // retrive all quotes data (aos_quotes table) 
        $get_entry_list_result_a = $this->get_entry_list($session, $module_name);
        $d = $get_entry_list_result_a['entry_list']; // fetch entry_list means value of it's array
        $list["Result"] = array();
        $result = 0;
        $key1 = 0;
        $fullentry = 0;
        //retrive one by one id 
        foreach ($d as $key => $m) {
            $id = $m['id'];
            $list["Result"][$result] = $d[$key1];
            // $d=$aos_quotes->retrieve($id);
            // retrive one id in $id variable and get that id's detial through get_entry_quotes function.Full detial like productbundle and product.
            $get_entry[$fullentry] = $this->get_entry_quotes($session, $module_name, $id);
            $result++;
            $key1++;
            $fullentry++;
        }
        return $get_entry;
    }

// retrive detail of particular one id quotes
    function get_entry_quotes($session, $module_name, $id) {
        global $sugar_config;
        //retrive detail of $id 
        $get_entry_quotes = $this->get_entry($session, $module_name, $id, $select_fields, $link_name_to_fields_array);
        $d = $get_entry_quotes['entry_list'];
        foreach ($d as $key => $m) {
            $list["Result"] = $d[$key];
        }
        $aos_quotes = BeanFactory::getBean($module_name);
        $aos_quotes->retrieve($id);  //retrive that id's detial and load relationship
        $aos_quotes->load_relationship('aos_line_item_groups'); //load relationship with productbundle

        $list["Result"]["ProductBundle"] = array();
        $productbundle = 0;

        $enable_groups = (int) $sugar_config['aos']['lineItems']['enableGroups'];

        $aLineItemGroups = $aos_quotes->aos_line_item_groups->getBeans();

        $list["Result"]['enableGroups'] = $enable_groups;
        $aos_quotes->load_relationship('aos_products_quotes'); //load relatinship with product	
        $aLineItemProducts = $aos_quotes->aos_products_quotes->getBeans();

        if ($enable_groups && !empty($aLineItemGroups)) {
            foreach ($aLineItemGroups as $aost) {
                $list["Result"]["ProductBundle"][$productbundle] = array(
                    "name" => $aost->name,
                    "id" => $aost->id,
                    "total_amt" => $aost->total_amt,
                    "total_amt_usdollar" => $aost->total_amt_usdollar,
                    "discount_amount" => $aost->discount_amount,
                    "discount_amount_usdollar" => $aost->discount_amount_usdollar,
                    "subtotal_amount" => $aost->subtotal_amount,
                    "subtotal_amount_usdollar" => $aost->subtotal_amount_usdollar,
                    "tax_amount" => $aost->tax_amount,
                    "tax_amount_usdollar" => $aost->tax_amount_usdollar,
                    "subtotal_tax_amount" => $aost->subtotal_tax_amount,
                    "subtotal_tax_amount_usdollar" => $aost->subtotal_tax_amount_usdollar,
                    "total_amount" => $aost->total_amount,
                    "total_amount_usdollar" => $aost->total_amount_usdollar,
                    "parent_type" => $aost->parent_type,
                    "number" => $aost->number
                );
                $list["Result"]["ProductBundle"][$productbundle]['Product'] = $this->getQuoteProduct($aost->id,$aLineItemProducts);
                $productbundle++;
            }
        }
        else{
            $list["Result"]["ProductBundle"][$productbundle]['Product'] = $this->getQuoteProduct('',$aLineItemProducts);
        }
        return $list;
    }

    function getQuoteProduct($groupId, $aLineItemGroups) {
        $product = 0;
        $aQuoteProducts = array();
        if(!empty($groupId)){
            foreach ($aLineItemGroups as $lineItem) {
                if ($groupId == $lineItem->group_id) {
                    $aQuoteProducts[$product] = array(
                        "name" => $lineItem->name,
                        "id" => $lineItem->id,
                        "part_number" => $lineItem->part_number,
                        "item_description" => $lineItem->item_description,
                        "product_qty" => $lineItem->product_qty,
                        "product_cost_price" => $lineItem->product_cost_price,
                        "product_cost_price_usdollar" => $lineItem->product_cost_price_usdollar,
                        "product_list_price" => $lineItem->product_list_price,
                        "product_list_price_usdollar" => $lineItem->product_list_price_usdollar,
                        "product_discount" => $lineItem->product_discount,
                        "product_discount_usdollar" => $lineItem->product_discount_usdollar,
                        "product_discount_amount" => $lineItem->product_discount_amount,
                        "product_discount_amount_usdollar" => $lineItem->product_discount_amount_usdollar,
                        "discount" => $lineItem->discount,
                        "product_unit_price" => $lineItem->product_unit_price,
                        "product_unit_price_usdollar" => $lineItem->product_unit_price_usdollar,
                        "vat_amt" => $lineItem->vat_amt,
                        "vat_amt_usdollar" => $lineItem->vat_amt_usdollar,
                        "product_total_price" => $lineItem->product_total_price,
                        "product_total_price_usdollar" => $lineItem->product_total_price_usdollar,
                        "vat" => $lineItem->vat,
                        "parent_type" => $lineItem->parent_type,
                    );
                    $product++;
                }
            }
        }
        else {
            foreach ($aLineItemGroups as $lineItem) {
                $aQuoteProducts[$product] = array(
                    "name" => $lineItem->name,
                    "id" => $lineItem->id,
                    "part_number" => $lineItem->part_number,
                    "item_description" => $lineItem->item_description,
                    "product_qty" => $lineItem->product_qty,
                    "product_cost_price" => $lineItem->product_cost_price,
                    "product_cost_price_usdollar" => $lineItem->product_cost_price_usdollar,
                    "product_list_price" => $lineItem->product_list_price,
                    "product_list_price_usdollar" => $lineItem->product_list_price_usdollar,
                    "product_discount" => $lineItem->product_discount,
                    "product_discount_usdollar" => $lineItem->product_discount_usdollar,
                    "product_discount_amount" => $lineItem->product_discount_amount,
                    "product_discount_amount_usdollar" => $lineItem->product_discount_amount_usdollar,
                    "discount" => $lineItem->discount,
                    "product_unit_price" => $lineItem->product_unit_price,
                    "product_unit_price_usdollar" => $lineItem->product_unit_price_usdollar,
                    "vat_amt" => $lineItem->vat_amt,
                    "vat_amt_usdollar" => $lineItem->vat_amt_usdollar,
                    "product_total_price" => $lineItem->product_total_price,
                    "product_total_price_usdollar" => $lineItem->product_total_price_usdollar,
                    "vat" => $lineItem->vat,
                    "parent_type" => $lineItem->parent_type,
                );
                $product++;
            }
        }
        return $aQuoteProducts;
    }


    function set_entry_quotes($session, $quote_detail) {

        $quotes["Quotes"] = $this->saveQuoteData($session, $quote_detail);
        return $quotes;
    }

    public function saveQuoteData($session, $quote_detail) {

        $aQuote = $quote_detail;
        $focus = new AOS_Quotes();

        //If id is given for Quote then retrieve the record
        if (!empty($aQuote['id'])) {
            //   $isFromUpdate = true;
            $quote_id = $aQuote['id'];
            $focus->retrieve($quote_id);
        }

        isset($aQuote['name']) ? $focus->name = $aQuote['name'] : '';
        isset($aQuote['approval_issue']) ? $focus->approval_issue = $aQuote['approval_issue'] : '';
        isset($aQuote['billing_account_id']) ? $focus->billing_account_id = $aQuote['billing_account_id'] : '';
        isset($aQuote['billing_contact_id']) ? $focus->billing_contact_id = $aQuote['billing_contact_id'] : '';
        isset($aQuote['billing_address_street']) ? $focus->billing_address_street = $aQuote['billing_address_street'] : '';
        isset($aQuote['billing_address_city']) ? $focus->billing_address_city = $aQuote['billing_address_city'] : '';
        isset($aQuote['billing_address_state']) ? $focus->billing_address_state = $aQuote['billing_address_state'] : '';
        isset($aQuote['billing_address_postalcode']) ? $focus->billing_address_postalcode = $aQuote['billing_address_postalcode'] : '';
        isset($aQuote['billing_address_country']) ? $focus->billing_address_country = $aQuote['billing_address_country'] : '';
        isset($aQuote['shipping_address_street']) ? $focus->shipping_address_street = $aQuote['shipping_address_street'] : '';
        isset($aQuote['shipping_address_city']) ? $focus->shipping_address_city = $aQuote['shipping_address_city'] : '';
        isset($aQuote['shipping_address_state']) ? $focus->shipping_address_state = $aQuote['shipping_address_state'] : '';
        isset($aQuote['shipping_address_postalcode']) ? $focus->shipping_address_postalcode = $aQuote['shipping_address_postalcode'] : '';
        isset($aQuote['shipping_address_country']) ? $focus->shipping_address_country = $aQuote['shipping_address_country'] : '';
        isset($aQuote['expiration']) ? $focus->expiration = $aQuote['expiration'] : '';
        isset($aQuote['number']) ? $focus->number = $aQuote['number'] : '';
        isset($aQuote['opportunity_id']) ? $focus->opportunity_id = $aQuote['opportunity_id'] : '';
        isset($aQuote['template_ddown_c']) ? $focus->template_ddown_c = $aQuote['template_ddown_c'] : '';
        isset($aQuote['total_amt']) ? $focus->total_amt = $aQuote['total_amt'] : '';
        isset($aQuote['shipping_address_postalcode']) ? $focus->shipping_address_postalcode = $aQuote['shipping_address_postalcode'] : '';
        isset($aQuote['total_amt_usdollar']) ? $focus->total_amt_usdollar = $aQuote['total_amt_usdollar'] : '';
        isset($aQuote['subtotal_amount']) ? $focus->subtotal_amount = $aQuote['subtotal_amount'] : '';
        isset($aQuote['subtotal_amount_usdollar']) ? $focus->subtotal_amount_usdollar = $aQuote['subtotal_amount_usdollar'] : '';
        isset($aQuote['discount_amount']) ? $focus->discount_amount = $aQuote['discount_amount'] : '';
        isset($aQuote['discount_amount_usdollar']) ? $focus->discount_amount_usdollar = $aQuote['discount_amount_usdollar'] : '';
        isset($aQuote['tax_amount']) ? $focus->tax_amount = $aQuote['tax_amount'] : '';
        isset($aQuote['tax_amount_usdollar']) ? $focus->tax_amount_usdollar = $aQuote['tax_amount_usdollar'] : '';
        isset($aQuote['shipping_amount']) ? $focus->shipping_amount = $aQuote['shipping_amount'] : '';
        isset($aQuote['shipping_amount_usdollar']) ? $focus->shipping_amount_usdollar = $aQuote['shipping_amount_usdollar'] : '';
        isset($aQuote['shipping_tax']) ? $focus->shipping_tax = $aQuote['shipping_tax'] : '';
        isset($aQuote['shipping_tax_amt']) ? $focus->shipping_tax_amt = $aQuote['shipping_tax_amt'] : '';
        isset($aQuote['shipping_tax_amt_usdollar']) ? $focus->shipping_tax_amt_usdollar = $aQuote['shipping_tax_amt_usdollar'] : '';
        isset($aQuote['total_amount']) ? $focus->total_amount = $aQuote['total_amount'] : '';
        isset($aQuote['total_amount_usdollar']) ? $focus->total_amount_usdollar = $aQuote['total_amount_usdollar'] : '';
        isset($aQuote['currency_id']) ? $focus->currency_id = $aQuote['currency_id'] : '';
        isset($aQuote['stage']) ? $focus->stage = $aQuote['stage'] : '';
        isset($aQuote['term']) ? $focus->term = $aQuote['term'] : '';
        isset($aQuote['terms_c']) ? $focus->terms_c = $aQuote['terms_c'] : '';
        isset($aQuote['approval_status']) ? $focus->approval_status = $aQuote['approval_status'] : '';
        isset($aQuote['invoice_status']) ? $focus->invoice_status = $aQuote['invoice_status'] : '';
        isset($aQuote['subtotal_tax_amount']) ? $focus->subtotal_tax_amount = $aQuote['subtotal_tax_amount'] : '';
        isset($aQuote['subtotal_tax_amount_usdollar']) ? $focus->subtotal_tax_amount_usdollar = $aQuote['subtotal_tax_amount_usdollar'] : '';

        //we have to commit the teams here in order to obtain the team_set_id for use with products and product bundles.
        if (empty($focus->aos_line_item_groups)) {
            $focus->load_relationship('aos_line_item_groups');
            $focus->load_relationship('aos_products_quotes');
        }
        $focus->save(); // Quote Saved
//        $f = $aQuote['id'] = $focus->id;
        $focus->retrieve($focus->id);


        $counter_productBundles = 0; // ProductBundle Counter 
        $aproductBundles = $aQuote['Productbundles'];

        //Setting Product Bundle Counter & values of Product bundle to another array for fetching values later

        foreach ($aproductBundles as $name => $value) {

            $aQuote['Productbundles'][$counter_productBundles] = $this->saveProductBundles($session, $value, $focus, $counter_productBundles);
            $counter_productBundles++;
        }

        $del_productbundle = $aQuote['deleted_productbundle'];
        foreach ($del_productbundle as $key => $value) {

            foreach ($del_productbundle[$key] as $key1 => $value) {

                if (isset($del_productbundle[$key]['id'])) {
                    $del_productbundle_id = $del_productbundle[$key]['id'];

                    //     require 'data\SugarBean.php';
                    $pb12 = new AOS_Line_Item_Groups();
                    $d = $pb12->retrieve($del_productbundle_id);
                    $pb12->load_relationship('aos_products_quotes');

                    $list = array();
                    foreach ($pb12->aos_products_quotes->getBeans() as $quotes) {

                        $quotes1 = $list[$quotes->id] = $quotes;
                        $f = $quotes1->id;
                    }
                    $product = new AOS_Products_Quotes();
                    $product->mark_deleted($f);
                    $pb12->mark_deleted($del_productbundle_id);
                }
            }
        }

        $del_product = $aQuote['deleted_product'];
        foreach ($del_product as $key => $value) {
            // $del_product_group_id=$del_product[$key]['group_id']   ;
            foreach ($del_product[$key]['product_id'] as $key2 => $value) {
                if (isset($del_product[$key]['product_id'][$key2]['id'])) {
                    $del_produc_id = $del_product[$key]['product_id'][$key2]['id'];
                    $product12 = new AOS_Products_Quotes();
                    $product12->retrieve($del_produc_id);
                    $product12->mark_deleted($del_produc_id);
//                        $focus->load_relationship('aos_products_quotes');
//                        $focus->aos_products_quotes->delete($pb12->id, $del_produc_id);
//                        $focus->save();
                }
            }
        }
        return $aQuote;
    }

    function saveProductBundles($session, $productBundles, $focus, $index) {

        //Retrieve or set values of ProductBundles
        $pb = new AOS_Line_Item_Groups();
        //$pb->load_relationship('aos_line_item_groups');
        //If id is given for ProductBundle then retrieve the record

        if (isset($productBundles['id'])) {
            $pb->retrieve($productBundles['id']);
        }
        $pb->parent_id = $focus->id;

        $pb->currency_id = $focus->currency_id;

        // Save all the name->value list provided
        isset($productBundles['name']) ? $pb->name = $productBundles['name'] : '';
        isset($productBundles['id']) ? $pb->id = $productBundles['id'] : '';
        isset($productBundles['parent_id']) ? $pb->parent_id = $productBundles['parent_id'] : '';
        isset($productBundles['total_amt']) ? $pb->total_amt = ($productBundles['total_amt']) : '';
        isset($productBundles['total_amt_usdollar']) ? $pb->total_amt_usdollar = ($productBundles['total_amt_usdollar']) : '';
        isset($productBundles['discount_amount']) ? $pb->discount_amount = ($productBundles['discount_amount']) : '';
        isset($productBundles['discount_amount_usdollar']) ? $pb->discount_amount_usdollar = ($productBundles['discount_amount_usdollar']) : '';
        isset($productBundles['shipping_usdollar']) ? $pb->shipping_usdollar = ($productBundles['shipping_usdollar']) : '';
        isset($productBundles['parent_type']) ? $pb->parent_type = $productBundles['parent_type'] : '';
        isset($productBundles['subtotal_amount']) ? $pb->subtotal_amount = ($productBundles['subtotal_amount']) : '';
        isset($productBundles['subtotal_amount_usdollar']) ? $pb->subtotal_amount_usdollar = ($productBundles['subtotal_amount_usdollar']) : '';
        isset($productBundles['tax_amount']) ? $pb->tax_amount = ($productBundles['tax_amount']) : '';
        isset($productBundles['tax_amount_usdollar']) ? $pb->tax_amount_usdollar = ($productBundles['tax_amount_usdollar']) : '';
        isset($productBundles['subtotal_tax_amount']) ? $pb->subtotal_tax_amount = ($productBundles['subtotal_tax_amount']) : '';
        isset($productBundles['subtotal_tax_amount_usdollar']) ? $pb->subtotal_tax_amount_usdollar = ($productBundles['subtotal_tax_amount_usdollar']) : '';
        isset($productBundles['total_amount']) ? $pb->total_amount = $productBundles['total_amount'] : '';
        isset($productBundles['total_amount_usdollar']) ? $pb->total_amount_usdollar = $productBundles['total_amount_usdollar'] : '';
        isset($productBundles['number']) ? $pb->number = ($productBundles['number']) : '';
        isset($productBundles['currency_id']) ? $pb->currency_id = $productBundles['currency_id'] : '';

        $pb->save();
        $productBundles['id'] = $pb->id;

        //Save Products
        $product_index = 0;

        if (isset($productBundles['Product'])) {
            foreach ($productBundles['Product'] as $key => $products) {

                $productBundles['Product'][$product_index] = $this->saveProducts($session, $products, $pb->id, $focus, $product_index);
                $product_index++;
            }
        }

        $result = $productBundles;

        return $result;
    }

    public function saveProducts($session, $products, $productBundleId, $focus, $product_index) {

        $product = new AOS_Products_Quotes();
        $product->currency_id = $focus->currency_id;
        $product->parent_id = $focus->id;
        $product->group_id = $productBundleId;
        $product->assigned_user_id = $focus->assigned_user_id;
        $product->assigned_user_name = $focus->assigned_user_name;

        // $product->quote_id = $focus->id;
        $product->account_id = $focus->billing_account_id;
        $product->contact_id = $focus->billing_contact_id;
        $product->ignoreQuoteSave = true;

        if (isset($products['id'])) {
            $id = $products['id'];
            $product->retrieve($id);
        }

        if (isset($products['product_id'])) {
            if ((isset($_products['product_id'])) && (isset($_products['id']))) {
                $id1 = $products['id'];
                $product->retrieve($id1);
                $aos_product1 = new AOS_Products();
                $aos_product1->retrieve($products['product_id']);
            }
            $aos_product1 = new AOS_Products();
            $aos_product1->retrieve($products['product_id']);
            $product->name = $aos_product1->name;
            $product->product_id = $aos_product1->id;
            $product->product_list_price = $aos_product1->price;
            $product->product_list_price_usdollar = $aos_product1->price_usdoller;
            $product->parent_type = "AOS_Quotes";
            $product->part_number = $aos_product1->part_number;
            $product->product_cost_price = $aos_product1->cost;
            $product->product_cost_price_usdollar = $aos_product1->cost_usdollar;
            $product->currency_id = $aos_product1->cuurency_id;
            $product->save();
            //     }
        }
        isset($products['name']) ? $product->name = $products['name'] : '';
        isset($products['product_list_price']) ? $product->product_list_price = $products['product_list_price'] : '';
        isset($products['part_number']) ? $product->part_number = $products['part_number'] : '';
        isset($products['item_description']) ? $product->item_description = $products['item_description'] : '';
        isset($products['number']) ? $product->number = $products['number'] : '';
        isset($products['product_qty']) ? $product->product_qty = $products['product_qty'] : '';
        isset($products['product_cost_price']) ? $product->product_cost_price = $products['product_cost_price'] : '';
        isset($products['product_cost_price_usdollar']) ? $product->product_cost_price_usdollar = $products['product_cost_price_usdollar'] : '';
        isset($products['product_list_price']) ? $product->product_list_price = $products['product_list_price'] : '';
        isset($products['product_list_price_usdollar']) ? $product->product_list_price_usdollar = $products['product_list_price_usdollar'] : '';
        isset($products['product_discount']) ? $product->product_discount = $products['product_discount'] : '';
        isset($products['product_discount_usdollar']) ? $product->product_discount_usdollar = $products['product_discount_usdollar	'] : '';
        isset($products['product_discount_amount']) ? $product->product_discount_amount = $products['product_discount_amount'] : '';
        isset($products['product_discount_amount_usdollar']) ? $product->product_discount_amount_usdollar = $products['product_discount_amount_usdollar'] : '';
        isset($products['discount']) ? $product->discount = $products['discount'] : '';
        isset($products['product_unit_price']) ? $product->product_unit_price = $products['product_unit_price'] : '';
        isset($products['product_unit_price_usdollar']) ? $product->product_unit_price_usdollar = $products['product_unit_price_usdollar'] : '';
        isset($products['vat_amt']) ? $product->vat_amt = $products['vat_amt'] : '';
        isset($products['vat_amt_usdollar']) ? $product->vat_amt_usdollar = $products['vat_amt_usdollar'] : '';
        isset($products['product_total_price']) ? $product->product_total_price = $products['product_total_price'] : '';
        isset($products['product_total_price_usdollar']) ? $product->product_total_price_usdollar = $products['product_total_price_usdollar'] : '';
        isset($products['vat']) ? $product->vat = $products['vat'] : '';
        isset($products['parent_type']) ? $product->parent_type = $products['parent_type'] : '';
        isset($products['parent_id']) ? $product->parent_id = $products['parent_id'] : '';
        $product->save();

        $products['id'] = $product->id;

        return $products;
    }
    function get_moduleLayouts($session, $module_name) {
        $views = array('list', 'edit', 'detail');
        $layout_data['module_name'] = $module_name;
        foreach ($views as $view) {
            $layout_data['layout'][$view] = $this->get_customListlayout($session, $module_name, $view);
        }
        return $layout_data;
    }

function forgotPassword($username,$email) {
        require_once 'include/SugarPHPMailer.php';
        global $db;
        $query = "SELECT contacts.id, contacts_cstm.username_c,contacts_cstm.password_c,email_addresses.email_address "
                . "FROM contacts LEFT JOIN contacts_cstm "
                . "ON contacts.id = contacts_cstm.id_c "
                . "LEFT JOIN email_addr_bean_rel "
                . "ON email_addr_bean_rel.bean_id = contacts.id "
                . "LEFT JOIN email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id "
                . "WHERE email_addresses.opt_out=0 "
                . "AND contacts_cstm.username_c = '{$username}' "
                . "AND email_addr_bean_rel.deleted = 0";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        if (strtolower($row['email_address']) == strtolower($email)) {
            $objAdministration = new Administration();
            $objAdministration->retrieveSettings('PortalPlugin');
            $forgotpasswordEmail = (!empty($objAdministration->settings['PortalPlugin_ForgotPasswordEmail'])) ? $objAdministration->settings['PortalPlugin_ForgotPasswordEmail'] : "0";
            // Send Email to newly cretaed contact
            $emailtemplateObj = new EmailTemplate();
            $emailtemplateObj->retrieve($forgotpasswordEmail);
            $macro_nv = array();
            $emailtemplateObj->parsed_entities = null;
            $emailSubjectName = $emailtemplateObj->subject;
        
            $email_module = 'Contacts';
            $recip_prefix = '$contact';

            // Email Template Body
            $objContacts = new Contact();
            $objContacts->retrieve($row['id']);
            $emailtemplateObj->body_html = str_replace($search_prefix, $recip_prefix, $emailtemplateObj->body_html);
            $template_data = $emailtemplateObj->parse_email_template(array(
                "subject" => $emailSubjectName,
                "body_html" => $emailtemplateObj->body_html,
                "body" => $emailtemplateObj->body), $email_module, $objContacts, $macro_nv);

            $emailBody = $template_data["body_html"];
            $mailSubject = $template_data["subject"];
            $GLOBALS['log']->fatal('This is the $emailBody : --- ', print_r($emailBody, 1));
            $emailSubject = $mailSubject;

            $to_Email = $objContacts->email1;
            require_once 'custom/biz/function/default_portal_module.php';
            $sendMail = CustomSendEmailPortal($to_Email, $emailSubject, $emailBody, $objContacts->id, $email_module); // send username password email
            $GLOBALS['log']->fatal("Username and Password sent mail status : " . print_r($sendMail, 1));
            if ($sendMail != "send") {
                $detail = array("result" => "false",
                    "message" => "Mail not Send"
                );
        } else {
                $detail = array("result" => "true",
                    "message" => "Mail Send SuccessFully"
                );
            }
        } else {
            $detail = array("result" => "false",
                "message" => "Enter Valid Username Or EmailId");
        }
            return $detail;
        }

function signupContact($session, $contact_detail) {
        global $db;
        $message = array();
        $getUsernameQuery = "SELECT contacts.id,contacts_cstm.username_c AS username
                FROM contacts
                JOIN contacts_cstm ON contacts.id = contacts_cstm.id_c AND contacts.deleted = 0
                WHERE contacts_cstm.username_c = '{$contact_detail['contact_detail']['username_c']}'";
        $getEmailQuery = "SELECT email_addr_bean_rel.bean_id,email_addresses.email_address AS email
                FROM email_addresses
                LEFT JOIN email_addr_bean_rel 
                    ON email_addresses.id = email_addr_bean_rel.email_address_id AND email_addresses.deleted = 0 AND email_addr_bean_rel.deleted = 0
                WHERE email_addresses.email_address = '{$contact_detail['contact_detail']['email1']}' AND email_addr_bean_rel.bean_module = 'Contacts'";

        $run_Query = $db->query($getEmailQuery);
        if ($run_Query->num_rows > 0) {
            while ($result_Query = $db->fetchByAssoc($run_Query)) {
                $ID = $result_Query['bean_id'];
                $contact = new Contact();
                $contact->retrieve($ID);
                if (($contact->username_c == $contact_detail['contact_detail']['username_c']) && ($contact->username_c != '')) {
                    $message['field'] = 'username_c,email1';
                    $message['error-msg'] = 'Username and EmailAddress are already exists.';
                } else {
                    $message['field'] = 'email1';
                    $message['error-msg'] = 'EmailAddress is already exists.';
                }
            }
        }
        $username_result = $db->query($getUsernameQuery);
        if ($username_result->num_rows > 0) {
            while ($result_Query = $db->fetchByAssoc($username_result)) {
                $ID = $result_Query['id'];
                $contact = new Contact();
                $contact->retrieve($ID);
                if (($contact->email1 == $contact_detail['contact_detail']['email1']) && ($contact->email1 != '')) {
                    $message['field'] = 'username_c,email1';
                    $message['error-msg'] = 'Username and EmailAddress are already exists.';
                } else {
                    $message['field'] = 'username_c';
                    $message['error-msg'] = 'Username is already exists.';
                }
            }
        }
        if (empty($message)) {
            $result = array();
            $contact = new Contact();
            foreach ($contact_detail['contact_detail'] as $key => $value) {
                $contact->$key = $value;
                $result[$key] = $value;
            }
       
            $contact->save();
            $result['id'] = $contact->id;
            return $result;
        } else {
            return $message;
        }
    }

    function generatePDF($session, $quote_id, $module_name) {

        require_once('modules/AOS_PDF_Templates/PDF_Lib/mpdf.php');
        require_once('modules/AOS_PDF_Templates/templateParser.php');
        require_once('modules/AOS_PDF_Templates/sendEmail.php');
        require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');
        global $db;
if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
        $sql = "SELECT id, name FROM aos_pdf_templates WHERE deleted=0 AND type='{$module_name}' AND active = 1";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $template_id = $row['id'];
            break;
        }
        if (!empty($template_id)) {
            $bean = BeanFactory::getBean($module_name, $quote_id);

            if (!$bean) {
                sugar_die("Invalid Record");
            }

            $task = 'pdf';
            $variableName = strtolower($bean->module_dir);
            $lineItemsGroups = array();
            $lineItems = array();

            $sql = "SELECT pg.id, pg.product_id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $bean->object_name . "' AND pg.parent_id = '" . $bean->id . "' AND pg.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
            $res = $bean->db->query($sql);
            while ($row = $bean->db->fetchByAssoc($res)) {
                $lineItemsGroups[$row['group_id']][$row['id']] = $row['product_id'];
                $lineItems[$row['id']] = $row['product_id'];
            }


            $template = new AOS_PDF_Templates();
            $template->retrieve($template_id);
            $object_arr = array();
            $object_arr[$bean->module_dir] = $bean->id;

//backward compatibility
            $object_arr['Accounts'] = $bean->billing_account_id;
            $object_arr['Contacts'] = $bean->billing_contact_id;
            $object_arr['Users'] = $bean->assigned_user_id;
            $object_arr['Currencies'] = $bean->currency_id;

            $search = array('/<script[^>]*?>.*?<\/script>/si', // Strip out javascript
                '/<[\/\!]*?[^<>]*?>/si', // Strip out HTML tags
                '/([\r\n])[\s]+/', // Strip out white space
                '/&(quot|#34);/i', // Replace HTML entities
                '/&(amp|#38);/i',
                '/&(lt|#60);/i',
                '/&(gt|#62);/i',
                '/&(nbsp|#160);/i',
                '/&(iexcl|#161);/i',
                '/<address[^>]*?>/si',
                '/&(apos|#0*39);/',
                '/&#(\d+);/'
            );

            $replace = array('',
                '',
                '\1',
                '"',
                '&',
                '<',
                '>',
                ' ',
                chr(161),
                '<br>',
                "'",
                'chr(%1)'
            );
            $header = preg_replace($search, $replace, $template->pdfheader);
            $footer = preg_replace($search, $replace, $template->pdffooter);
            $text = preg_replace($search, $replace, $template->description);
            $text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
            $text = preg_replace_callback('/\{DATE\s+(.*?)\}/', function ($matches) {
                return date($matches[1]);
            }, $text);
            $text = str_replace("\$aos_quotes", "\$" . $variableName, $text);
            $text = str_replace("\$aos_invoices", "\$" . $variableName, $text);
            $text = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $text);
            $text = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $text);
            $text = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $text);
            $text = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $text);
            $text = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $text);
            $text = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $text);

            $text = $this->populate_group_lines_custom($text, $lineItemsGroups, $lineItems);
            $converted = templateParser::parse_template($text, $object_arr);
            $header = templateParser::parse_template($header, $object_arr);
            $footer = templateParser::parse_template($footer, $object_arr);

            $printable = str_replace("\n", "<br />", $converted);

            $file_name = str_replace(" ", "_", $bean->name) . ".pdf";
            ob_clean();
            try {
                $pdf = new mPDF('en', 'A4', '', 'DejaVuSansCondensed', $template->margin_left, $template->margin_right, $template->margin_top, $template->margin_bottom, $template->margin_header, $template->margin_footer);
                $pdf->SetAutoFont();
                $pdf->SetHTMLHeader($header);
                $pdf->SetHTMLFooter($footer);
                $pdf->WriteHTML($printable);
                if ($task == 'pdf') {
                    $pdf->Output($file_name, "F");
                }
            } catch (mPDF_exception $e) {
                echo $e;
            }
            $return_array = array();
            $return_array['file_name'] = $file_name;
            $return_array['file_content'] = base64_encode(file_get_contents($file_name));
            unlink($file_name);
            return $return_array;
        } else {
            $return_array['msg'] = "Pdf template does not exists.";
            return $return_array;
        }
    }

    function populate_group_lines_custom($text, $lineItemsGroups, $lineItems, $element = 'table') {
        $firstValue = '';
        $firstNum = 0;

        $lastValue = '';
        $lastNum = 0;

        $startElement = '<' . $element;
        $endElement = '</' . $element . '>';


        $groups = new AOS_Line_Item_Groups();
        foreach ($groups->field_defs as $name => $arr) {
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

                $curNum = strpos($text, '$aos_line_item_groups_' . $name);
                if ($curNum) {
                    if ($curNum < $firstNum || $firstNum == 0) {
                        $firstValue = '$aos_line_item_groups_' . $name;
                        $firstNum = $curNum;
                    }
                    if ($curNum > $lastNum) {
                        $lastValue = '$aos_line_item_groups_' . $name;
                        $lastNum = $curNum;
                    }
                }
            }
        }
        if ($firstValue !== '' && $lastValue !== '') {
            //Converting Text
            $parts = explode($firstValue, $text);
            $text = $parts[0];
            $parts = explode($lastValue, $parts[1]);
            if ($lastValue == $firstValue) {
                $groupPart = $firstValue . $parts[0];
            } else {
                $groupPart = $firstValue . $parts[0] . $lastValue;
            }

            if (count($lineItemsGroups) != 0) {
                //Read line start <tr> value
                $tcount = strrpos($text, $startElement);
                $lsValue = substr($text, $tcount);
                $tcount = strpos($lsValue, ">") + 1;
                $lsValue = substr($lsValue, 0, $tcount);


                //Read line end values
                $tcount = strpos($parts[1], $endElement) + strlen($endElement);
                $leValue = substr($parts[1], 0, $tcount);

                //Converting Line Items
                $obb = array();

                $tdTemp = explode($lsValue, $text);

                $groupPart = $lsValue . $tdTemp[count($tdTemp) - 1] . $groupPart . $leValue;

                $text = $tdTemp[0];

                foreach ($lineItemsGroups as $group_id => $lineItemsArray) {
                    $groupPartTemp = $this->populate_product_lines_custom($groupPart, $lineItemsArray);
                    $groupPartTemp = $this->populate_service_lines_custom($groupPartTemp, $lineItemsArray);

                    $obb['AOS_Line_Item_Groups'] = $group_id;
                    $text .= templateParser::parse_template($groupPartTemp, $obb);
                    $text .= '<br />';
                }
                $tcount = strpos($parts[1], $endElement) + strlen($endElement);
                $parts[1] = substr($parts[1], $tcount);
            } else {
                $tcount = strrpos($text, $startElement);
                $text = substr($text, 0, $tcount);

                $tcount = strpos($parts[1], $endElement) + strlen($endElement);
                $parts[1] = substr($parts[1], $tcount);
            }

            $text .= $parts[1];
        } else {
            $text = $this->populate_product_lines_custom($text, $lineItems);
            $text = $this->populate_service_lines_custom($text, $lineItems);
        }
        return $text;
    }

    function populate_product_lines_custom($text, $lineItems, $element = 'tr') {
        $firstValue = '';
        $firstNum = 0;

        $lastValue = '';
        $lastNum = 0;

        $startElement = '<' . $element;
        $endElement = '</' . $element . '>';

        //Find first and last valid line values
        $product_quote = new AOS_Products_Quotes();
        foreach ($product_quote->field_defs as $name => $arr) {
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

                $curNum = strpos($text, '$aos_products_quotes_' . $name);

                if ($curNum) {
                    if ($curNum < $firstNum || $firstNum == 0) {
                        $firstValue = '$aos_products_quotes_' . $name;
                        $firstNum = $curNum;
                    }
                    if ($curNum > $lastNum) {
                        $lastValue = '$aos_products_quotes_' . $name;
                        $lastNum = $curNum;
                    }
                }
            }
        }

        $product = new AOS_Products();
        foreach ($product->field_defs as $name => $arr) {
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

                $curNum = strpos($text, '$aos_products_' . $name);
                if ($curNum) {
                    if ($curNum < $firstNum || $firstNum == 0) {
                        $firstValue = '$aos_products_' . $name;


                        $firstNum = $curNum;
                    }
                    if ($curNum > $lastNum) {
                        $lastValue = '$aos_products_' . $name;
                        $lastNum = $curNum;
                    }
                }
            }
        }

        if ($firstValue !== '' && $lastValue !== '') {

            //Converting Text
            $tparts = explode($firstValue, $text);
            $temp = $tparts[0];

            //check if there is only one line item
            if ($firstNum == $lastNum) {
                $linePart = $firstValue;
            } else {
                $tparts = explode($lastValue, $tparts[1]);
                $linePart = $firstValue . $tparts[0] . $lastValue;
            }


            $tcount = strrpos($temp, $startElement);
            $lsValue = substr($temp, $tcount);
            $tcount = strpos($lsValue, ">") + 1;
            $lsValue = substr($lsValue, 0, $tcount);

            //Read line end values
            $tcount = strpos($tparts[1], $endElement) + strlen($endElement);
            $leValue = substr($tparts[1], 0, $tcount);
            $tdTemp = explode($lsValue, $temp);

            $linePart = $lsValue . $tdTemp[count($tdTemp) - 1] . $linePart . $leValue;
            $parts = explode($linePart, $text);
            $text = $parts[0];

            //Converting Line Items
            if (count($lineItems) != 0) {
                foreach ($lineItems as $id => $productId) {
                    if ($productId != null && $productId != '0') {
                        $obb['AOS_Products_Quotes'] = $id;
                        $obb['AOS_Products'] = $productId;
                        $text .= templateParser::parse_template($linePart, $obb);
                    }
                }
            }

            $text .= $parts[1];
        }
        return $text;
    }

    function populate_service_lines_custom($text, $lineItems, $element = 'tr') {
        $firstValue = '';
        $firstNum = 0;

        $lastValue = '';
        $lastNum = 0;

        $startElement = '<' . $element;
        $endElement = '</' . $element . '>';

        $text = str_replace("\$aos_services_quotes_service", "\$aos_services_quotes_product", $text);

        //Find first and last valid line values
        $product_quote = new AOS_Products_Quotes();
        foreach ($product_quote->field_defs as $name => $arr) {
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link')) {

                $curNum = strpos($text, '$aos_services_quotes_' . $name);
                if ($curNum) {
                    if ($curNum < $firstNum || $firstNum == 0) {
                        $firstValue = '$aos_products_quotes_' . $name;
                        $firstNum = $curNum;
                    }
                    if ($curNum > $lastNum) {
                        $lastValue = '$aos_products_quotes_' . $name;
                        $lastNum = $curNum;
                    }
                }
            }
        }
        if ($firstValue !== '' && $lastValue !== '') {
            $text = str_replace("\$aos_products", "\$aos_null", $text);
            $text = str_replace("\$aos_services", "\$aos_products", $text);

            //Converting Text
            $tparts = explode($firstValue, $text);
            $temp = $tparts[0];

            //check if there is only one line item
            if ($firstNum == $lastNum) {
                $linePart = $firstValue;
            } else {
                $tparts = explode($lastValue, $tparts[1]);
                $linePart = $firstValue . $tparts[0] . $lastValue;
            }

            $tcount = strrpos($temp, $startElement);
            $lsValue = substr($temp, $tcount);
            $tcount = strpos($lsValue, ">") + 1;
            $lsValue = substr($lsValue, 0, $tcount);

            //Read line end values
            $tcount = strpos($tparts[1], $endElement) + strlen($endElement);
            $leValue = substr($tparts[1], 0, $tcount);
            $tdTemp = explode($lsValue, $temp);

            $linePart = $lsValue . $tdTemp[count($tdTemp) - 1] . $linePart . $leValue;
            $parts = explode($linePart, $text);
            $text = $parts[0];

            //Converting Line Items
            if (count($lineItems) != 0) {
                foreach ($lineItems as $id => $productId) {
                    if ($productId == null || $productId == '0') {
                        $obb['AOS_Products_Quotes'] = $id;
                        $text .= templateParser::parse_template($linePart, $obb);
                    }
                }
            }

            $text .= $parts[1];
        }
        return $text;
    }

    function set_profile_picture($session, $content) {
        global $sugar_config;
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
        $data = array();
        // $GLOBALS['log']->fatal('Begin: SugarWebServiceImpl->set_portal_picture');

        if ($content['id'] != "" && $content['filename'] != "" && $content['file'] != "" && $content['action'] == "add") {
            if (file_exists($sugar_config['cache_dir'] . 'images/' . $content['id'] . '_' . $content['filename'])) {
                unlink($sugar_config['cache_dir'] . 'images/' . $content['id'] . '_' . $content['filename']);
            }
            file_put_contents($sugar_config['cache_dir'] . 'images/' . $content['id'] . '_' . $content['filename'], base64_decode($content['file']));
            $contact = new Contact();
            $contact->retrieve($content['id']);
            $contact->contact_photo = $content['filename'];
            $contact->save();
            $data['profile_photo'] = $sugar_config['site_url'] . '/' . $sugar_config['cache_dir'] . 'images/' . $contact->id . '_' . $content['filename'];
            $data['success'] = 1;
        } else if ($content['action'] == "remove" && $content['id'] != "") {
            $contact = new Contact();
            $contact->retrieve($content['id']);
            $filename = $contact->contact_photo;
            if ($filename != "") {
                $contact->contact_photo = '';
                $path = $sugar_config['site_url'] . '/' . $sugar_config['cache_dir'] . 'images/' . $content['id'] . '_' . $filename;
                if (file_exists($path)) {
                    unlink($path);
                }
                $contact->save();
                $data['msg'] = 'Removed successfully';
                $data['success'] = 1;
            } else {
                $data['msg'] = 'No photo found for ' . $content['id'];
                $data['success'] = 0;
            }
        } else {
            $data['msg'] = 'please provide all neccessary details to upload image';
            $data['action'] = $content['action'];
            $data['success'] = 0;
        }

        return $data;
    }

    function get_profile_picture($session, $content) {
    if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		
        global $sugar_config;
        $data = array();
        $contact_id = $content['id'];
        if ($contact_id != "") {
            $contact = new Contact();
            $contact->retrieve($contact_id);
            $contact_photo = $contact->contact_photo;
            if ($contact_photo != "") {
                //  $GLOBALS['log']->fatal('$contact_photo' . $contact_photo);
                $path = file_get_contents($sugar_config['site_url'] . '/' . $sugar_config['cache_dir'] . 'images/' . $contact_id . '_' . $contact_photo);
                $data['profile_photo'] = base64_encode($path);
                $data['success'] = 1;
            } else {
                $data['profile_photo'] = 'no photo found';
                //$data['success'] = 1;
                $data['msg'] = 'no photo found';
            }
        } else {
            //$data['success'] = 0;
            $data['msg'] = 'please provide contact id.';
        }
        return $data;
    }

    public function globalSearch($session, $contact_id, $searchDetail) {
if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
}
        require_once "modules/MySettings/TabController.php";
        require_once 'modules/Home/UnifiedSearchAdvanced.php';
        require_once 'include/utils.php';

        $call = new SugarWebServiceImplv4();
        //$accessLevel = $this->getPortal_accessibleModules($session, $contact_id);
        if (!empty($searchDetail['module_list'])) {
            $searchModulelist = $searchDetail['module_list'];
        } else {
            $usa = new UnifiedSearchAdvanced();
            $unified_search_modules_display = $usa->getUnifiedSearchModulesDisplay();
            $users_modules = $GLOBALS['current_user']->getPreference('globalSearch', 'search');

            if (!empty($users_modules)) {
                foreach ($users_modules as $key => $value) {
                    if (isset($unified_search_modules_display[$key]) && !empty($unified_search_modules_display[$key]['visible'])) {
                        $searchModulelist[$key] = $key;
                    }
                }
            } else {
                foreach ($unified_search_modules_display as $key => $data) {
                    if (!empty($data['visible'])) {
                        $searchModulelist[$key] = $key;
                    }
                }
            }
            if (isset($searchModulelist[$this->module])) {
                unset($searchModulelist[$this->module]);
                $searchModulelist = array_merge(array($this->module => $this->module), $modules);
            }
        }
        $Result = array();
        $count = 0;

        foreach ($searchModulelist as $module) {
            $search_records = array();

            if ($module == 'AOK_KnowledgeBase') {
                $searchResult = $call->search_by_module($session, $searchDetail['search_string'], array($module), '', $searchDetail['max_result'], '', array('name', 'status', 'author', 'approver', 'revision', 'date_entered', 'date_modified', 'created_from_c', 'id', 'description'), FALSE);
            } else {
                $searchResult = $call->search_by_module($session, $searchDetail['search_string'], array($module), '', $searchDetail['max_result'], '', array(), FALSE);
            }
            $GLOBALS['log']->fatal('This is the $searchResult : --- ', print_r($searchResult, 1));

            foreach ($searchResult['entry_list'] as $key => $recordDetail) {
                $total = 0;
                foreach ($recordDetail['records'] as $num => $record) {

                    $link_field_name = strtolower($module);

                    $related_fields = array(
                        '0' => 'name',
                        '1' => 'date_entered',
                        '2' => 'id'
                    );
                    $entryListResult = $this->get_relationships($session, 'Contacts', $contact_id, $link_field_name, '', $related_fields, array(), '0', '');

                    $entrylistid = array();
                    foreach ($entryListResult['entry_list'] as $keyvalue => $getid) {
                        $entrylistid[] = $getid['id'];
                    }

                    foreach ($entryListResult['entry_list'] as $kk => $id) {
                        if (in_array($record['id']['value'], $entrylistid) && $record['id']['value'] == $id['id']) {
                            $Result[$module]['entry_list'][$count] = $entryListResult['entry_list'][$kk];
                            $count++;
                        }
                    }

                    if ($module == 'AOK_KnowledgeBase') {
                        $Result[$module]['entry_list'][$count] = $record;
                    }
                    $total++;
                }
            }
            $res++;
        }
        if (empty($Result)) {
            $Result['msg'] = 'No records found';
        }
        return $Result;
    }

    function getChartdetails($session, $contact_id) {
        global $app_list_strings, $db;
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('PortalPlugin');
        $chartEnable = json_decode(html_entity_decode($administrationObj->settings['PortalPlugin_PortalChart']));
        $chart_array = array();
        foreach ($chartEnable as $module => $enable) {
            if ($enable == "true" && $module == 'case') {
                $query_cases = $db->query("SELECT COUNT(*) as cnt,cases.status FROM cases JOIN contacts_cases ON cases.id = contacts_cases.case_id WHERE cases.deleted=0 AND contacts_cases.deleted = 0 AND contacts_cases.contact_id='{$contact_id}' GROUP BY cases.status");
                while ($fetch_cases = $db->fetchByAssoc($query_cases)) {
                    if (!empty($fetch_cases['status'])) {
                        $case_status = $app_list_strings['case_status_dom'][$fetch_cases['status']];
                    } else {
                        $case_status = 'Other';
                    }
                    $chart_array['cases'][] = array('count' => $fetch_cases['cnt'], 'status' => $case_status);
                }
            }
            if ($enable == "true" && $module == 'invoice') {
                $query_cases = $db->query("SELECT COUNT(*) as cnt,aos_invoices.status FROM aos_invoices WHERE aos_invoices.deleted=0 AND aos_invoices.billing_contact_id='{$contact_id}' GROUP BY aos_invoices.status");
                while ($fetch_invoice = $db->fetchByAssoc($query_cases)) {
                    if (!empty($fetch_invoice['status'])) {
                        $invoice_status = $app_list_strings['invoice_status_dom'][$fetch_invoice['status']];
                    } else {
                        $invoice_status = 'Other';
                    }
                    $chart_array['invoice'][] = array('count' => $fetch_invoice['cnt'], 'status' => $invoice_status);
                }
            }
            if ($enable == "true" && $module == 'quotes') {
                $query_cases = $db->query("SELECT COUNT(*) as cnt,aos_quotes.stage FROM aos_quotes WHERE aos_quotes.deleted=0 AND aos_quotes.billing_contact_id='{$contact_id}' GROUP BY aos_quotes.stage");
                while ($fetch_quotes = $db->fetchByAssoc($query_cases)) {
                    if (!empty($fetch_quotes['stage'])) {
                        $quotes_stage = $app_list_strings['in_total_group_stages'][$fetch_quotes['stage']];
                    } else {
                        $quotes_stage = 'Other';
                    }
                    $chart_array['quotes'][] = array('count' => $fetch_quotes['cnt'], 'status' => $quotes_stage);
                }
            }
        }
        return $chart_array;
    }

    public function getPortalConnectionMessageList($session, $lang = 'en_us') {

        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }

        $AppLang = return_app_list_strings_language($lang);
        $PortalAppLang = $AppLang['portal_connection_message_list'];
        return $PortalAppLang;
    }

    public function getPortalLoginMessageList($session, $lang = 'en_us') {

        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }

        $AppLang = return_app_list_strings_language($lang);
        $PortalAppLang = $AppLang['portal_message_list'];
        return $PortalAppLang;
    }

    public function getPrimaryAccessibleModules($session) {

        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }

        global $app_list_strings;

        $oUserGroup = BeanFactory::getBean('bc_user_group');
        $oUserGroup->retrieve_by_string_fields(array('is_portal_accessible_group' => 1));

        $oUserGroup->portal_accessiable_module = str_replace('&quot;', '"', $oUserGroup->portal_accessiable_module);

        $result = array();
        if (!empty($oUserGroup->id) && !empty($oUserGroup->portal_accessiable_module)) {
            $acc_module = json_decode($oUserGroup->portal_accessiable_module);
            foreach ($acc_module as $module_key => $module_rights) {
                $result[$module_key] = $app_list_strings['moduleList'][$module_key];
            }
        } else {
            $acc_module = array('Accounts', 'Calls', 'Cases', 'Documents', 'Meetings', 'Notes', 'AOS_Quotes','AOS_Contracts','AOS_Invoices','AOK_KnowledgeBase');
            foreach ($acc_module as $module_key) {
                $result[$module_key] = $app_list_strings['moduleList'][$module_key];
            }
        }

        return $result;
    }
	
	/*Shatty Code*/
		public function get_option_list($session, $name_value_list) {
		  /*   		     
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		foreach($name_value_list as $dataIndex => $dataValue){
			if($dataValue['name'] == 'optionList'){
				$optionFieldName = $dataValue['value'];
			}
			
		}
		
		
        global $app_list_strings;

		$dataOption = $app_list_strings[$optionFieldName];

		foreach($dataOption as $index => $value){
			$arr = array( 'Unsupported Work' ,'test' , 'UNSUPPORTED_WORK_GROUP', 'Server Administration' , 'Scheduled Maintenance' );
			
			if(!in_array($index , $arr)){
				$myArray [$index] = $value; 
			}
		}
		return json_encode($myArray);
		
		$dataOption = json_encode($app_list_strings[$optionFieldName]);
        return $dataOption;
    }
	
	public function get_comments($session, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		foreach($name_value_list as $dataIndex => $dataValue){
			
			if($dataValue['name'] == 'ticketID'){
				$ticketID = $dataValue['value'];
			}
			
		}
		
        $ticketBean = BeanFactory::getBean('Cases',$ticketID);
            
		if ($ticketBean->load_relationship('cases_dg_comments_1'))
		{
			//Fetch related beans 
			$relatedBeans = $ticketBean->cases_dg_comments_1->getBeans();
		}
		
		foreach($relatedBeans as $index => $value){
			$dataArray[] = array('id' => $value->id,
					'message' => $value->description,
					'date' => $value->date_entered);
            
			
		}
            
        //$oUserGroup->retrieve_by_string_fields(array('is_portal_accessible_group' => 1));


        return json_encode($dataArray);
    }
	

	public function add_comments($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
          */  
		
		foreach($name_value_list as $dataIndex => $dataValue){
			if($dataValue['name'] == 'ticketID'){
				$ticketID = $dataValue['value'];
			}
			if($dataValue['name'] == 'description'){
				$description = $dataValue['value'];
				
			}
			
		}
		
		$beanComments = new dg_comments();
		$beanComments->name = 'Add From Portal';
		$beanComments->description = $description;
		$beanComments->save();
		$commentID = $beanComments->id;
		
		if ($beanComments->load_relationship('cases_dg_comments_1')){
			$beanComments->cases_dg_comments_1->add($ticketID);
			
		}
		
		global $db;
		
		$sDql = "SELECT case_number, assigned_user_id, category, account_id FROM cases WHERE id = '{$ticketID}'";
	
		$aResult = $db->query($sDql);
		$aRow = $db->fetchByAssoc($aResult);
		
		$aEmailConfig = array();
		
		$oAccBean = BeanFactory::getBean('Accounts', $aRow['account_id']);
		
		if(!empty($aRow['assigned_user_id'])){
			$oUserBean = BeanFactory::getBean('Users', $aRow['assigned_user_id']);	
			$aEmailConfig = array(
				'email' => $oUserBean->email1,
				'name' => "{$oUserBean->first_name} {$oUserBean->last_name}",
			);
		}
		
		require_once('custom/DigiboostMailer/DigiboostMailer.php'); 
		$oDigiMailer = new \DigiboostMailer();
		
		if(empty($sEmail)){
			$aEmailConfig = $oDigiMailer->getTicketReceiverByCategory($aRow['category']);
		}
		
		$sSubject = "Digiboost - Ticket #{$aRow['case_number']}"; 
		$oDigiMailer->sendEmail('tickets/comment/digiboost.txt', array(
			'name' => $aEmailConfig['name'],
			'case_number' => $aRow['case_number'],
			'customer_name' => $oAccBean->name,
			'comment' => $description,
			'id' => $ticketID
		), $sSubject, array(
			$aEmailConfig['email']
		));
		
        return json_encode(array('comment ID' => $commentID, 'Ticket ID' => $ticketID));

    }
	/* 22-03-2019 */
	public function get_ticket_data($name_value_list) {
			$username = $name_value_list['user_name'];
			$password = $name_value_list['password'];
			$ticketID = $name_value_list['ticketID'];
			$order_by = $name_value_list['order_by'];
			$condition = $name_value_list['condition'];
			
		
			$getUser = array(
			
				"user_name" => $username,
				"password" => md5($password),
				
			);
			$data = $this->login($getUser);
			
			if(empty($data) || !isset($data['id']) || empty($data['id'])){
				return "Login Details Invalid ! ";
			}
		
		
			if(!empty($ticketID)){
				$condition = " AND id = '$ticketID' "; 
			}
			if(!empty($order_by)){
				$order_by = " ORDER BY $order_by";
			}
		
		global $db;
		$sql = "SELECT caseUpdates.*, users.first_name as FirstName, users.last_name as LastName 
					FROM aop_case_updates as caseUpdates 
				JOIN users ON users.id = caseUpdates.assigned_user_id
				Where caseUpdates.case_id = '$ticketID' ORDER BY caseUpdates.date_entered ASC" ;

		$resultQuery = $db->query($sql);
		while($resultUp = $db->fetchByAssoc($resultQuery)){
			$casesUpdates[] = $resultUp;
		}
		
		$sqlCase = "SELECT name, subject, category, description, 
						date_entered, case_number, status, type, priority, resolution,  state, assigned_user_id
						FROM cases WHERE deleted = 0 $condition  $order_by" ;
		
		$sqlRes = $db->query($sqlCase);
		
		$result['Num_Of_Records'] = $sqlRes->num_rows;
		if($sqlRes->num_rows > 0){
			while ($data_case = $db->fetchByAssoc($sqlRes)){
				
				$resultCaseQuery[] = $data_case;
				
			}
			
		}
			$result['ticket_data'] = $resultCaseQuery;
			$result['case_updates'] = $casesUpdates;
		
		return json_encode($result);

    }

/* Add Ticket */
	
	public function add_ticket($session,$module_name, $name_value_list) {
		  /*   	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		
        global $db;
		$contactID = '';
		$bean = BeanFactory::newBean($module_name);
		foreach($name_value_list as $dataIndex => $dataValue){
			if($dataValue['name'] == 'account_no'){
				$accountNO = $dataValue['value'];
				$sql = "SELECT id_c as id FROM accounts_cstm Where account_number_c = '$accountNO'";
				$resultQuery = $db->query($sql);
				$result = $db->fetchByAssoc($resultQuery);
				$accID = $result['id'];
				if(!$accID){
					return json_encode( array('status' => 'Account Number Not Found' ));
				}
				//$oAccBean = BeanFactory::getBean('Accounts', $accID);	
			}else if($dataValue['name'] == 'subject'){
				$bean->subject = $dataValue['value'];
				$bean->name = $dataValue['value'];
			}
			else if($dataValue['name'] == 'status'){
				$bean->status = $dataValue['value'];
			}
			else if($dataValue['name'] == 'assigned_user_id'){
				$bean->assigned_user_id = ' ';
			}else if($dataValue['name'] == 'description'){
				$bean->description = $dataValue['value'];
			}else if($dataValue['name'] == 'priority'){
				$bean->priority = $dataValue['value'];
			}else if($dataValue['name'] == 'type'){
				$bean->type = $dataValue['value'];
			}else if($dataValue['name'] == 'contact_id'){
				$contactID = $dataValue['value'];
			}else if($dataValue['name'] == 'category'){
				$contactID = $dataValue['value'];
			}
		
		}
		
		
			$bean->account_id = $accID;
			$bean->name = $bean->subject;
			$bean->assigned_user_id = ' ';
			$bean->save();

		$ticketID = $bean->id;
			if(!empty($contactID)){			
				$sql = "INSERT INTO contacts_cases (id , contact_id, case_id ) values ('$id' , '$contactID' , '$ticketID')";
				$db->query($sql);
			}
			return (array('id' => $bean->id, 'entry_list' => $name_value_list));

	
	}	
/* get record contacts*/
	public function get_record_list_account($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		global $db;
		$recData = $this->get_entry_list($session, $module_name, $name_value_list['query'], $name_value_list['order_by'], $name_value_list['offset'], $name_value_list['select_fields'], $name_value_list['max_results']);
		$id = '';
		foreach($recData['entry_list'] as $index => $value){
			
			if($value['name_value_list']['account_id']['name'] == 'account_id'){
			
				$id = $value['name_value_list']['account_id']['value'];
				$sql = "SELECT account_number_c as account_number FROM accounts_cstm WHERE id_c = '$id'";
			
				$queryResult = $db->query($sql);
				$result = $db->fetchByAssoc($queryResult);
				$daat[] = $result;
				$recData['entry_list'][$index]['account number'] = $result['account_number'];
			
			}
			
		}
		return $recData;
		
    }	

							/*Return email check record data */	
	public function get_record_list_email($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		
		*/
		$param_query = explode("email_address", $name_value_list['query']);
		
		
		$emailID = trim(str_replace("=" , " ",$param_query[1]));

		
		global $db;
		
		$sql = "SELECT email_bean.bean_id as email_id FROM email_addr_bean_rel as email_bean JOIN email_addresses as em_add ON em_add.id = email_address_id WHERE email_bean.bean_module = 'Contacts' AND email_bean.deleted = 0 AND em_add.email_address = $emailID";
		$queryRes = $db->query($sql);
		$queryID = $db->fetchByAssoc($queryRes);
		$mod_query = $param_query[0].strtolower($module_name).".id = '". $queryID['email_id']."'";
		$recData = $this->get_entry_list($session, $module_name, $mod_query, $name_value_list['order_by'], $name_value_list['offset'], $name_value_list['select_fields'], $name_value_list['max_results']);
		return $recData;
		$id = '';
		foreach($recData['entry_list'] as $index => $value){
			
			if($value['name_value_list']['email_address']['name'] == 'email_address'){
			
				$email = $value['name_value_list']['email_address']['value'];
				return $email;
				$sql = "SELECT account_number_c as account_number FROM accounts_cstm WHERE id_c = '$email'";
			
				$queryResult = $db->query($sql);
				$result = $db->fetchByAssoc($queryResult);
				$daat[] = $result;
				
			
			}
			
		}
		return $recData;
		
    }


				/*********Return record data with related records **********/	
	public function get_record_related_bean($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		$recData = $this->get_entry_list($session, $module_name, $name_value_list['query'], $name_value_list['order_by'], $name_value_list['offset'], $name_value_list['select_fields'], $name_value_list['max_results']);
		
		global $sugar_config;
		$site_url = $sugar_config['site_url'];
		
		foreach($recData['entry_list'] as $index => $value){
			
			$id = $value['name_value_list']['id']['value'];
			/*
			if(!empty($value['name_value_list']['kb_image_c']['value'])){
				$value['name_value_list']['kb_image_c']['value'] = "$site_url/index.php?entryPoint=download&id=".$id."_kb_image_c&type=AOK_KnowledgeBase"; 
			}
				*/
			if($id){
				$finalRecord = '';
				//get categories
				$record_rel = $this->get_relationships($session, $module_name, $id, 'aok_knowledge_base_sub_cat_aok_knowledgebase_1', $related_module_query, $name_value_list['select_sub_category_fields'], $related_module_link_name_to_fields_array = array(), $deleted = 0, $order_by = '', $offset = 0, $limit = false);
			
				if(!empty($record_rel['entry_list'])){					
					
					foreach($record_rel['entry_list'] as $relIndex => $relValue){
						//get sub categories
						$record_sub_rel = '';
						$record_sub_rel = $this->get_relationships($session, $relValue['module_name'], $relValue['id'], 'aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1', $related_module_query, $name_value_list['select_parent_category_fields'], $related_module_link_name_to_fields_array = array(), $deleted = 0, $order_by = '', $offset = 0, $limit = false);
				//return $record_sub_rel;
						
						$relValue['Parent Category of Sub Category'] = $record_sub_rel;
						$finalRecord[] = $relValue;
						
					}
					
					$value['name_value_list']['KB Sub Categories'] = $finalRecord; 
					
				}
				
				$data[] = $value['name_value_list'];
			}
			
		}
		
		return $data;
		
    }
	
	
						/*********add Cases updates**********/	
	public function add_cases_update($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		
		*/
		foreach($name_value_list as $dataIndex => $dataValue){
			if($dataValue['name'] == 'ticket_id'){
				$caseId = $dataValue['value'];
				$data[]=$caseId;
			}
			else if($dataValue['name'] == 'description'){
				$name = $dataValue['value'];
				$data[]=$name;
			}
			else if($dataValue['name'] == 'internal'){
				$internal = $dataValue['value'];
				$data[]=$internal;
			}
			else if($dataValue['name'] == 'portalUserID'){
				$portal_user_id = $dataValue['value'];
				$data[]=$portal_user_id;
			}
			
			
			
		}
		//return $data;
		
		if(!empty($caseId)){
			
			global $db;
			$id = create_guid();
			$date = date("Y-m-d H:i:s");
			
			$sqlIn = "INSERT INTO aop_case_updates (id, name, date_entered, description, case_id, internal, portal_user_id, assigned_user_id) values ('$id','$name','$date','$name','$caseId','0', '$portal_user_id', '7ac941a3-1294-32ca-91c7-5b0c2bd708d7')" ;
			$resultQueryIn = $db->query($sqlIn);
			
			$sql = "SELECT caseUpdates.id, caseUpdates.name, caseUpdates.date_entered, caseUpdates.created_by, caseUpdates.case_id, caseUpdates.case_id, caseUpdates.portal_user_id, users.first_name as FirstName, users.last_name as LastName FROM aop_case_updates as caseUpdates 
					JOIN users ON users.id = caseUpdates.assigned_user_id
					Where caseUpdates.case_id = '$caseId' ORDER BY caseUpdates.date_entered ASC " ;
					
			$resultQuery = $db->query($sql);
			
			while($result = $db->fetchByAssoc($resultQuery)){
				$casesUpdates[] = $result;
			}
			$bean = BeanFactory::getBean('Cases', $caseId);
			if($bean->status == 'Closed_Closed'){
				$bean->status = 'Open_New';
				$bean->state = 'Open';
				$bean->save();
			}
		
			$sDql = "SELECT case_number, assigned_user_id, category, account_id FROM cases WHERE id = '{$caseId}'";
		
			$aResult = $db->query($sDql);
			$aRow = $db->fetchByAssoc($aResult);
			
			$aEmailConfig = array();
			
			$oAccBean = BeanFactory::getBean('Accounts', $aRow['account_id']);
			
			if(!empty($aRow['assigned_user_id'])){
				$oUserBean = BeanFactory::getBean('Users', $aRow['assigned_user_id']);	
				$aEmailConfig = array(
					'email' => $oUserBean->email1,
					'name' => "{$oUserBean->first_name} {$oUserBean->last_name}"
				);
			}
			
			require_once('custom/DigiboostMailer/DigiboostMailer.php'); 
			$oDigiMailer = new \DigiboostMailer();
			
			if(empty($sEmail)){
				$aEmailConfig = $oDigiMailer->getTicketReceiverByCategory($aRow['category']);
			}
			
			$sSubject = "Digiboost - Ticket #{$aRow['case_number']}"; 
			$oDigiMailer->sendEmail('tickets/update/digiboost.txt', array(
				'name' => $aEmailConfig['name'],
				'case_number' => $aRow['case_number'],
				'customer_name' => $oAccBean->name,
				'comment' => $name,
				'id' => $caseId
			), $sSubject, array(
				$aEmailConfig['email']
			));
			
			return $casesUpdates;
		}
		else{
			return "Please Provide Ticket ID ";
		}
		
    }
	
	function set_entry($session, $module_name, $name_value_list, $track_view = false){
		$aRes = parent::set_entry($session, $module_name, $name_value_list, $track_view = false);
		if($module_name === "Cases" && $name_value_list[0]['name'] === "status" && $name_value_list[0]['value'] === "Closed_Closed"){
			
			$caseId = $name_value_list[1]['value'];
			
			global $db;
			
			$sDql = "SELECT case_number, assigned_user_id, category, account_id FROM cases WHERE id = '{$caseId}'";
		
			$aResult = $db->query($sDql);
			$aRow = $db->fetchByAssoc($aResult);
			
			$aEmailConfig = array();
			
			$oAccBean = BeanFactory::getBean('Accounts', $aRow['account_id']);
			
			if(!empty($aRow['assigned_user_id'])){
				$oUserBean = BeanFactory::getBean('Users', $aRow['assigned_user_id']);	
				$aEmailConfig = array(
					'email' => $oUserBean->email1,
					'name' => "{$oUserBean->first_name} {$oUserBean->last_name}",
				);
			}
			
			require_once('custom/DigiboostMailer/DigiboostMailer.php'); 
			$oDigiMailer = new \DigiboostMailer();
			
			if(empty($sEmail)){
				$aEmailConfig = $oDigiMailer->getTicketReceiverByCategory($aRow['category']);
			}
			
			$sSubject = "Digiboost - Ticket #{$aRow['case_number']}"; 
			$oDigiMailer->sendEmail('tickets/close/digiboost.txt', array(
				'name' => $aEmailConfig['name'],
				'case_number' => $aRow['case_number'],
				'customer_name' => $oAccBean->name,
				'comment' => $name,
				'id' => $caseId
			), $sSubject, array(
				$aEmailConfig['email']
			));
		}
		return $aRes;
	}
	
			
					/*********Multi document upload**********/	
	public function upload_multi_doc($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		
		*/
		foreach($name_value_list as $dataIndex => $dataValue){
			
			foreach($dataValue as $indPath => $valPath){

				if($valPath['name'] == 'path'){
					$path = $valPath['value'];
				}
				if($valPath['name'] == 'ticketID'){
					$ticketID = $valPath['value'];
				}
			}
			
			
				$data = $this->set_entry($session,$module_name, $dataValue);					
						$document_ids[] = $data['id'];
						$document_id = $data['id'];
						//create document revision ------------------------------------ 
						$contents = file_get_contents ($path);

						$document_revision = array(
								//The ID of the parent document.
								'id' => $document_id,

								//The binary contents of the file.
								'file' => base64_encode($contents),

								//The name of the file
								'filename' => 'example_document.png',

								//The revision number
								'revision' => '1',
							);

						$revData[] = $this->set_document_revision($session, $document_revision);
				//$set_document_revision_result = call("set_document_revision", $set_document_revision_parameters, $url);
			
		}
		
		global $db;
		$date = date("Y-m-d h:i:s");
		foreach($document_ids as $indexID => $valueID){
			$id = create_guid();
			if(!empty($valueID)){
				$query = "INSERT INTO documents_cases (id, date_modified, document_id, case_id) VALUES ('$id', '$date', '$valueID', '$ticketID')";
				$db->query($query);
			}
		}
		
		return json_encode($data);
		

    }
	
	
							/*********Single Document Create**********/	
	public function portal_upload_doc($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		foreach($name_value_list as $dataIndex => $dataValue){
			
			if($dataValue['name'] == 'path'){
					$path = $dataValue['value'];
				}
				if($dataValue['name'] == 'document_name'){
					$document_name = $dataValue['value'];
				}

		}
		
		$data = $this->set_entry($session,$module_name, $name_value_list);
		
		
		$document_id = $data['id'];
		//return $name_value_list;
		//create document revision ------------------------------------ 
		$contents = file_get_contents ($path);

		$document_revision = array(
				//The ID of the parent document.
				'id' => $document_id,

				//The binary contents of the file.
				'file' => base64_encode($contents),

				//The name of the file
				'filename' => $document_name,

				//The revision number
				'revision' => '1',
			);

		$revData = $this->set_document_revision($session, $document_revision);
		return $data;
				

    }
	

							
			/*********Add Documents to Ticket**********/	
	public function add_doc_ticket($session,$module_name, $name_value_list) {
		/*     	
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		*/
		global $db;
		foreach($name_value_list as $index => $value){
			
			if($value['name'] == 'id'){
				
				$id = $value['value'];
			}
			if($value['name'] == 'images'){
				$images = $value['value'];
				foreach($images as $imgInd => $imgVal){
					//$imagePath = $imgVal['path'];
					
					$uploaddir = $_SERVER['DOCUMENT_ROOT']."/upload/multi_".$id."/update_attachment_c";
					//echo $_SERVER['DOCUMENT_ROOT'];return false;
					if (!is_dir($uploaddir)) {
						mkdir($uploaddir , 0777 , true);
					}
					
					
					$imageName .= $imgVal.':';
				}
			}
			
		}
		
		
		$imagesNames = rtrim($imageName,":");
		$queryGet = "SELECT update_attachment_c FROM `cases_cstm` WHERE id_c = '$id'";
		$result = $db->query($queryGet);
		$dbImages = $db->fetchByAssoc($result);
		if(!$dbImages){
			return array('Error' => 'Please Give Correct Record ID');
		}
		
		if(!empty($dbImages['update_attachment_c'])){		
			$imagesNames = $imagesNames.':'.$dbImages['update_attachment_c'];
		}
		
		
		$query = "Update cases_cstm SET update_attachment_c = '$imagesNames' WHERE id_c = '$id'";
		$result = $db->query($query);
		
		$sDql = "SELECT case_number, assigned_user_id, category, account_id FROM cases WHERE id = '{$id}'";
	
		$aResult = $db->query($sDql);
		$aRow = $db->fetchByAssoc($aResult);
		
		$aEmailConfig = array();
		
		$oAccBean = BeanFactory::getBean('Accounts', $aRow['account_id']);
		
		if(!empty($aRow['assigned_user_id'])){
			$oUserBean = BeanFactory::getBean('Users', $aRow['assigned_user_id']);	
			$aEmailConfig = array(
				'email' => $oUserBean->email1,
				'name' => "{$oUserBean->first_name} {$oUserBean->last_name}",
			);
		}
		
		require_once('custom/DigiboostMailer/DigiboostMailer.php'); 
		$oDigiMailer = new \DigiboostMailer();
		
		if(empty($sEmail)){
			$aEmailConfig = $oDigiMailer->getTicketReceiverByCategory($aRow['category']);
		}
		
		$sSubject = "Digiboost - Ticket #{$aRow['case_number']}"; 
		$oDigiMailer->sendEmail('tickets/attachment/digiboost.txt', array(
			'name' => $aEmailConfig['name'],
			'customer_name' => $oAccBean->name,
			'id' => $id
		), $sSubject, array(
			$aEmailConfig['email']
		));
		
		return array('New Added Documents' => $images , 'OLD Documents' => explode(":",$dbImages['update_attachment_c']) , 'File Path' => 'https://digiboost.com/upload/multi_'.$id.'/update_attachment_c/');
				
    }
	
	
	/* for testing purpose */
	
	
		/*Shatty Code*/
		public function testing_list($session, $name_value_list) {
		
        if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', '', '', '', $error)) {
            $GLOBALS['log']->info('End: SugarWebServiceImplv4_1_custom->set_resume.');
            return false;
        }
		
		foreach($name_value_list as $dataIndex => $dataValue){
			if($dataValue['name'] == 'optionList'){
				$optionFieldName = $dataValue['value'];
			}
			
		}
		
        global $app_list_strings;

		$dataOption = $app_list_strings[$optionFieldName];
		
		foreach($dataOption as $index => $value){
			$arr = array( 'Unsupported Work' ,'test' , 'UNSUPPORTED_WORK_GROUP', 'Server Administration' , 'Scheduled Maintenance' );
			
			if(!in_array($index , $arr)){
				$myArray [$index] = $value; 
			}
		}
		return json_encode($myArray);
		
		$dataOption = json_encode($app_list_strings[$optionFieldName]);
        return $dataOption;
    }
	
			/*17-03-2019 Code*/
	public function get_document($name_value_list) {
		$username = $name_value_list['user_name'];
		$password = $name_value_list['password'];
		$query_param = $name_value_list['query_params'];
		$order_by = $name_value_list['order_by'];
		
		$getUser = array(
			
				"user_name" => $username,
				"password" => md5($password),
				
			);
		$data = $this->login($getUser);
		$id = $data['name_value_list']['user_id']['value'];
		
			global $db;
			$sql = "SELECT main.* , custom.contact_id_c as contact_id , custom.account_id_c as account_id  FROM documents as main
			JOIN documents_cstm as custom ON custom.id_c = main.id  WHERE main.deleted = 0";


			$sql = "SELECT 
					main.id, 
					custom.contact_id_c as contact_id,
					custom.account_id_c as account_id, 
					main.document_name,
					main.description,
					main.active_date,
					main.exp_date,
					main.category_id,
					main.subcategory_id
					FROM documents as main
					JOIN documents_cstm as custom 
						ON custom.id_c = main.id  
					WHERE main.deleted = 0 AND main.assigned_user_id = '$id' $query_param $order_by";
			$result = $db->query($sql);
			$data_array = [];
			$cont_rec = [];
			
			if ($result->num_rows > 0) {

				// output data of each row
				while($row = $result->fetch_assoc()) {


				$acc_id = $row['account_id'];
				$con_id = $row['contact_id'];
/*				
				if($acc_id != ''){
					$sql_j = "SELECT contacts.id as cont_id,
								contacts.first_name , 
								contacts.last_name 
								FROM accounts_contacts as acc_cont 
							JOIN contacts ON contacts.id = acc_cont.contact_id 
								WHERE acc_cont.account_id = '$acc_id' AND acc_cont.deleted = 0 AND contacts.deleted = 0";
					
					//echo $sql_j;die;
					$sql_res = $db->query($sql_j);
					if ($sql_res->num_rows > 0) {				
						while($res = $sql_res->fetch_assoc()){
							
							$cont_rec[] = $res;
							
						}
						$row['contact_list'] = $cont_rec;
					}
					
				}
				if($con_id != ''){
					$con_sql = "SELECT first_name , last_name FROM contacts WHERE id = '$con_id'";
					$con_res = $db->query($con_sql);
					if ($con_res->num_rows > 0) {				
						$res = $con_res->fetch_assoc();
						$row['portal_user'] = $res['first_name']. ' '. $res['last_name'];
					}
				}
*/
				$data_array[] = $row;

				}
			}
			
			$mydata = json_encode($data_array);
			return $mydata;
		
		
       
    }
	
}
