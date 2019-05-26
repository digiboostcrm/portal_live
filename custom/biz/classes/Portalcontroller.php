<?php

/**
 * The file used to handle layout actions
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once 'include/utils.php';
require_once 'custom/biz/classes/Portalutils.php';
require_once 'modules/ModuleBuilder/parsers/parser.label.php';
require_once 'modules/ModuleBuilder/Module/StudioModuleFactory.php';
require_once 'modules/ModuleBuilder/parsers/views/GridLayoutMetaDataParser.php';
require_once ('modules/ModuleBuilder/parsers/ParserFactory.php');
require_once 'include/MVC/View/SugarView.php';
require_once 'include/utils.php';

class Portalcontroller {

    /**
     * Description :: This function is used to get module fields of a particular module.
     * 
     * @return array '$fields_defination' - Array fields matadata
     */
    function getModuleFields() {
        require_once("modules/MySettings/TabController.php");
        // this function is used to display module fields
        global $db, $beanList, $mod_strings;
        $module = $_POST['module_name'];
        $module_obj = new $beanList[$module]();
        $field_defination = $module_obj->getFieldDefinitions();
        $layout_type = $_REQUEST['layout_type'];

        if ($module == 'Accounts') {
            if ($layout_type == 'edit') {
                $restirct_fields = array('id', 'created_by', 'modified_user_id', 'date_entered', 'date_modified', 'modified_by_name', 'created_by_name', 'team_name', 'case_number','parent_name','assigned_user_name','created_from_c');
            } else if ($layout_type == 'detail' || $layout_type == 'list') {
                $restirct_fields = array('id', 'created_from_c', 'team_name', 'created_by', 'modified_user_id', 'modified_by_name', 'created_by_name','parent_name','assigned_user_name','created_from_c');
            }
        } else {
            if ($layout_type == 'edit') {
                $restirct_fields = array('id', 'created_by', 'modified_user_id', 'date_entered', 'date_modified', 'modified_by_name', 'created_by_name', 'team_name', 'case_number','assigned_user_name','created_from_c','portal_flag','revision');
            } else if ($layout_type == 'detail' || $layout_type == 'list') {
                $restirct_fields = array('id', 'created_from_c', 'team_name', 'created_by', 'modified_user_id', 'modified_by_name', 'created_by_name','assigned_user_name','created_from_c','portal_flag');
            }
        }

        $v = new SugarView(null, array());
        $v->module = $module;
        $v->type = $layout_type;
        $metadataFile = $v->getMetaDataFile();

// Differentiate file for Sugar7 and Sugar6/Suite To Escape Ondemand Issue.
        if (!empty($metadataFile) && file_exists($metadataFile)) {
            $aAvailableFields = $this->getAvailableFields($layout_type . 'view', $module);
            // Modified By SP 13/07/2016 --start

            foreach ($aAvailableFields as $key => $value) {
                if (!array_search($value['name'], $restirct_fields)) {
                    $module_fields[$value['name']] = $value['name'];
                }
            }
            // -- End SP
        } else {
            foreach ($field_defination as $key => $def) {
                if ($this->isValidStudioField($def) && (
                        (
                        (
                        (
                        empty($def ['source']) || $def ['source'] == 'db' || $def ['source'] == 'custom_fields' || ($def ['source'] == 'non-db')
                        ) && (
                        isset($def ['type']) &&
                        $def ['type'] != 'id' && $def ['type'] != 'parent_type' && $def['type'] != 'readonly'
                        ) && (
                        ($layout_type == 'edit' && (!isset($def ['readonly']) || (isset($def ['readonly']) &&
                        $def ['readonly'] != true ) ) || $layout_type != 'edit')
                        ) && (
                        empty($def ['dbType']) || $def ['dbType'] != 'id'
                        ) && (
                        ($def ['studio'] !== 'false' && $def ['studio'] !== false)
                        ) && (
                        isset($def ['name']) && strcmp($def ['name'], 'deleted') != 0 )
                        ) || (
                        isset($def['name']) && ($def['name'] === 'email1' || substr($def['name'], -5) === '_name')
                        )
                        ) && (!array_search($key, $restirct_fields))
                        )) {

                    $module_fields[$key] = $key;
                }
            }
        }

        if ($module == 'Documents') {
            $module_fields['filename'] = 'filename';
        }
        // set layout by displaying set fields and all fields in different block
        foreach ($field_defination as $field_name => $defination) {
            if (array_search($field_name, $module_fields)) {
                foreach ($defination as $key => $value) {
                    $fields_defination['allfields'][$field_name][$key] = $value;
                    $fields_defination['setfields'][$field_name][$key] = $value;
                    // Start-- Changes Of required Feild validation
                    if ($key == 'required') {
                        if ($value) {
                            $fields_defination['allfields'][$field_name]['default_require'] = 'true';
                            $fields_defination['setfields'][$field_name]['default_require'] = 'true';
                        }
                    }
                    //End 
                    if ($key == 'vname') {
                        $targetModuleLang = return_module_language('en_us', $module_obj->module_dir);
                        $lableValue = $targetModuleLang[$value];
                        if (empty($lableValue)) {
                            $targetAppLang = return_application_language('en_us');
                            $lableValue = $targetAppLang[$value];
                        }
                        $fields_defination['allfields'][$field_name]['label_value'] = ($lableValue != '') ? $lableValue : $field_name;
                        $fields_defination['setfields'][$field_name]['label_value'] = ($lableValue != '') ? $lableValue : $field_name;
                    }
                }
            }
        }

        // get selected fields that is saved in defs file
        if ($layout_type == 'list') {

            $get_data_query = "select layout from portal_layout where module_name='$module' and layout_type='ListView'";
            $get_layout = $db->query($get_data_query);
            while ($row = $db->fetchByassoc($get_layout)) {
                $layout_data = html_entity_decode($row['layout']);
                $layout_fields = json_decode($layout_data);
            }

            $setLayoutFields = array();
            foreach ($layout_fields as $key => $field) {
                $name = $field->name;
                $list_field_name = strtolower($name);
                unset($fields_defination['allfields'][$list_field_name]);
                $setLayoutFields[] = $fields_defination['setfields'][$list_field_name];
            }
            $fields_defination['setfields'] = $setLayoutFields;
        }

        if ($layout_type == 'edit' || $layout_type == 'detail') {

            if ($layout_type == 'edit') {
                // get fields from db table
                $get_data_query = "select layout,panel_index from portal_layout where module_name='$module' and layout_type='EditView'";
                $get_layout = $db->query($get_data_query);
                while ($row = $db->fetchByassoc($get_layout)) {
                    $layout_data = html_entity_decode($row['layout']);
                    $panel_index_seq = $row['panel_index'];
                    $layout_fields = json_decode($layout_data);
                }
            }

            if ($layout_type == 'detail') {
                // get fields from db table
                $get_data_query = "select layout ,panel_index from portal_layout where module_name='$module' and layout_type='DetailView'";
                $get_layout = $db->query($get_data_query);
                while ($row = $db->fetchByassoc($get_layout)) {
                    $layout_data = html_entity_decode($row['layout']);
                    $panel_index_seq = $row['panel_index'];

                    $layout_fields = json_decode($layout_data);
                }
            }

            if (isset($layout_fields)) {
                $editlayout_panel = array();
                $panelNo = 0;

                $controller = new TabController();
                $tabs = $controller->get_tabs_system(); //get 

                $remove_key = array();
                foreach ($fields_defination['allfields'] as $k => $v) {

                    if (isset($v['module']) && $v['module'] != "" && in_array($v['module'], $tabs[1])) {
                        $remove_key[] = $v['name'];
                        unset($fields_defination['allfields'][$k]);
                    }
                }

                foreach ($fields_defination['setfields'] as $k => $v) {

                    if (isset($v['module']) && $v['module'] != "" && in_array($v['module'], $tabs[1])) {
                        unset($fields_defination['setfields'][$k]);
                    }
                }



                foreach ($layout_fields as $panel_index => $panel) {
                    foreach ($panel as $panel_key => $panel_value) {
                        $editlayout_panel[$panelNo] = array();
                        $editlayout_rows = array();
                        foreach ($panel_value as $row_index => $rows) {
                            $editlayout_rows[$row_index] = array();
                            foreach ($rows as $col_index => $column) {

                                $name = $column->name;
                                $list_field_name = strtolower($name);
                                // Start-- Changes Of required Feild validation
                                if (isset($fields_defination['allfields'][$list_field_name]['default_require'])) {
                                    $column->default_require = 'true';
                                }
                                // End
                                unset($fields_defination['allfields'][$list_field_name]);
                                unset($fields_defination['setfields']);
                                $targetModuleLang = return_module_language('en_us', $module_obj->module_dir);

                                $lableValue = $targetModuleLang[$column->label];
                                if (empty($lableValue)) {
                                    $targetAppLang = return_application_language('en_us');
                                    $lableValue = $targetAppLang[$column->label];
                                }

                                if (!in_array($column->name, $remove_key)) {
                                    $editlayout_rows[$row_index][$col_index] = array(
                                        'name' => $column->name,
                                        'label' => $column->label,
                                        'type' => $column->type,
                                        'required' => $column->required,
                                        'options' => $field_defination[$column->name]['options'],
                                        'label_value' => $lableValue,
                                        'id_name' => $column->id_name,
                                        'default_require' => $column->default_require,
                                    );
                                } else {
                                    $editlayout_rows[$row_index][$col_index] = array();
                                }
                            }
                        }

//                        $editlayout_panel[$panelNo]['panel_name'] = $targetModuleLang[$panel_key];
                        $editlayout_panel[$panelNo]['panel_name'] = (empty($targetModuleLang[$panel_key]) || is_null($targetModuleLang[$panel_key])) ? $mod_strings[$panel_key] : $targetModuleLang[$panel_key];
                        $editlayout_panel[$panelNo]['panel_id'] = $panel_key;
                        $editlayout_panel[$panelNo]['rows'] = $editlayout_rows;
                        $panelNo++;
                    }
                    $fields_defination['setfields']['module'] = $editlayout_panel;
                    $fields_defination['setfields']['panel_index'] = $panel_index_seq;
                }
            } else {
                unset($fields_defination['setfields']);
            }
        }

        return $fields_defination;
    }

    /**
     * Description :: This function is used to set ListView layout of a particular module.
     * 
     * @return bool '$result' - true - layout is set
     *                          false - layout not set
     */
    function isValidStudioField(
    $def
    ) {
        if (isset($def['studio'])) {
            if (is_array($def ['studio'])) {
                if (isset($def['studio']['editField']) && $def['studio']['editField'] == true)
                    return true;
                if (isset($def['studio']['required']) && $def['studio']['required'])
                    return true;
            } else {
                if ($def['studio'] == 'visible')
                    return true;
                if ($def['studio'] == 'hidden' || $def['studio'] == 'false' || !$def['studio'])
                    return false;
            }
        }
        if (empty($def ['source']) || $def ['source'] == 'db' || $def ['source'] == 'custom_fields') {
            if ($def ['type'] != 'id' && (empty($def ['dbType']) || $def ['dbType'] != 'id'))
                return true;
        }
        if ($def ['type'] == 'relate') {
            return true;
        }
        return false;
    }

    /**
     * Added New function which filter fileds based on passed module and layout type.
     * 
     * @author Biztech SP
     * @param string $layout_type 
     * @param string $module 
     * @return array available fields of modules.
     *   */
    function getAvailableFields($layout_type, $module) {
        global $sugar_version, $sugar_config, $sugar_flavor;
        $re_sugar_versionForPro75 = '/(7\.5\.[0-9])/';
        $re_sugar_versionForPro76 = '/(7\.6\.[0-9])/';
        $re_sugar_versionCE = '/(6\.5\.[0-9])/';

        if (preg_match($re_sugar_versionForPro75, $sugar_version) || preg_match($re_sugar_versionForPro76, $sugar_version)) {
            if ($layout_type == 'editview' || $layout_type == 'detailview') {
                if (isModuleBWC($module)) {
                    $parser2 = ParserFactory::getParser('editview', $module);
                } else {
                    $parser2 = ParserFactory::getParser('recordview', $module);
                }
            } else {
                $parser2 = ParserFactory::getParser($layout_type, $module);
            }
        } else {
            $parser2 = ParserFactory::getParser($layout_type, $module);
        }

        // Obtain the full list of valid fields in this module
        $availableFields = array();
        foreach ($parser2->_fielddefs as $key => $def) {
            if (GridLayoutMetaDataParser::validField($def, $parser2->_view) || isset($parser2->_originalViewDef[$key])) {
                //If the field original label existing, we should use the original label instead the label in its fielddefs.
                if (isset($parser2->_originalViewDef[$key]) && is_array($parser2->_originalViewDef[$key]) && isset($parser2->_originalViewDef[$key]['label'])) {
                    $availableFields [$key] = array('name' => $key, 'label' => $parser2->_originalViewDef[$key]['label']);
                } else {
                    $availableFields [$key] = array('name' => $key, 'label' => isset($def ['label']) ? $def ['label'] : $def['vname']); // layouts use 'label' not 'vname' for the label entry
                }

                $availableFields[$key]['translatedLabel'] = translate(isset($def ['label']) ? $def ['label'] : $def['vname'], $module);
            }
        }
        if ($layout_type == 'listview') {
            $list_layout = new ListLayoutMetaDataParser($layout_type, $module);
            $origDefs = $list_layout->getOriginalViewDefs();
            foreach ($origDefs as $key => $def) {
                if ((isset($parser2->_fielddefs)) && ($parser2->_fielddefs[$key]['studio'] != 'false')) {
                    if (!array_key_exists($key, $availableFields)) {

                        $availableFields[$key] = $def;
                        $availableFields[$key] ['name'] = $key;
                    }
                }
            }
        }
        return $availableFields;
    }

    function saveListLayout() {
        global $db, $timeDate;
        $gmtdatetime = TimeDate::getInstance()->nowDb();
        $module = $_REQUEST['sel_module'];
        $layoutFieldsArray = json_decode(html_entity_decode($_REQUEST['layoutFieldsArray']),true);
        $field_sequence = 1;
        foreach ($layoutFieldsArray as $key => $fields_array) {
            $fields_array['field_sequence'] = $field_sequence;
            $layoutFieldsArray[$key] = $fields_array;
            $field_sequence++;
        }

        $layout = json_encode($layoutFieldsArray);
        $unique_id = create_guid();

        // get fields from db table
        $check_existance_query = "select * from portal_layout where module_name='$module' and layout_type='ListView'";
        $getResult = $db->query($check_existance_query);
        $ResultCount = $db->fetchByAssoc($getResult);
        $layoutContent = addslashes($layout);
        if (empty($ResultCount)) {
            //insert new record to db
            $save_listview_query = "Insert into portal_layout values('$unique_id','$module','ListView','$layoutContent','$gmtdatetime','0')";
            $result = $db->query($save_listview_query);
        } else {
            //update existing record to db
            $save_listview_query = "Update portal_layout set layout='$layoutContent',modified_date='$gmtdatetime' where module_name='$module' and layout_type='ListView'";
            $result = $db->query($save_listview_query);
        }

        return $result;
    }

    /**
     * Description :: This function is used to set EditView and/or DetailView layout of a particular module.
     *
     * @return bool '$result' - true - layout is set
     *                          false - layout not set
     */
    function saveEditLayout() {
        global $db, $app_list_strings, $timeDate;
        $gmtdatetime = TimeDate::getInstance()->nowDb();
        $module = $_REQUEST['sel_module'];
        $panel_index_seq = $_REQUEST['panel_index'];
        $layoutFieldsArray = json_decode(html_entity_decode($_REQUEST['layoutFieldsArray']),true);
        $layout_type = $_REQUEST['layout_type'];
        $isViewSynced = $_REQUEST['is_synced'];
        $panelLable = array();
        $panel_sequence = 1;
        foreach ($layoutFieldsArray as $panel_index => $panel) {
            $panelID = 'LBL_' . strtoupper(str_replace(' ', '_', $panel['panel_name']));

            if (!empty($editlayout_rows['panels'][$panelID])) {
                $panelID = $panelID . "_" . $panel_index;
            }

            $editlayout_rows['panels'][$panelID] = array();
            $panelLable['label_' . $panelID] = $panel['panel_name'];
            $row_sequence = 1;

            foreach ($panel['rows'] as $row_index => $rows) {
                $editlayout_rows['panels'][$panelID][$row_index] = array();
                $col_sequence = 1;
                foreach ($rows as $col_index => $column) {
                    if ($column['name'] == '') {
                        $editlayout_rows['panels'][$panelID][$row_index][$col_index] = array();
                    } else {
                        $options = array();
                        $check = explode(',', $column['options']);

                        if (strstr($column['options'], ',') != FALSE) {
                            foreach ($check as $key => $val) {
                                $options[$val] = array('name' => $val, 'value' => $val);
                            }
                        } else {
                            $array = $app_list_strings[$column['options']];

                            foreach ($array as $key => $val) {
                                $options[$val] = array('name' => $val, 'value' => $key);
                            }
                        }
                        if ($column['id_name'] != '') {
                            $editlayout_rows['panels'][$panelID][$row_index][$col_index] = array(
                                'name' => $column['name'],
                                'label_value' => $column['label_value'],
                                'label' => $column['label'],
                                'type' => $column['type'],
                                'required' => $column['required'],
                                'options' => $options,
                                'id_name' => $column['id_name'],
                                'field_sequence' => $col_sequence,
                                'row_number' => $row_sequence,
                                'panel_number' => $panel_sequence,
                            );
                        } else {
                            $editlayout_rows['panels'][$panelID][$row_index][$col_index] = array(
                                'name' => $column['name'],
                                'label_value' => $column['label_value'],
                                'label' => $column['label'],
                                'type' => $column['type'],
                                'required' => $column['required'],
                                'options' => $options,
                                'field_sequence' => $col_sequence,
                                'row_number' => $row_sequence,
                                'panel_number' => $panel_sequence,
                            );
                        }
                        $col_sequence++;
                    }
                }
                $row_sequence++;
            }
            $panel_sequence++;
        }
        if ($layout_type == 'edit') {
            $unique_id = create_guid();
            $layout = json_encode($editlayout_rows);
            // get fields from db table
            $check_existance_query = "select * from portal_layout where module_name='$module' and layout_type='EditView'";
            $getResult = $db->query($check_existance_query);
            $ResultCount = $db->fetchByAssoc($getResult);
            $layoutContent = addslashes($layout);
            if (empty($ResultCount)) {
                //insert new record to db
                $save_editview_query = "Insert into portal_layout values('$unique_id','$module','EditView','$layoutContent','$gmtdatetime','$panel_index_seq')";
                $result = $db->query($save_editview_query);
            } else {
                //update existing record to db
                $save_editview_query = "Update portal_layout set layout='$layoutContent',modified_date='$gmtdatetime',panel_index='$panel_index_seq' where module_name='$module' and layout_type='EditView'";

                $result = $db->query($save_editview_query);
            }
        }
        if ($layout_type == 'detail' || $isViewSynced) {

            $unique_id = create_guid();
            $layout = json_encode($editlayout_rows);
            // get fields from db table
            $check_existance_query = "select * from portal_layout where module_name='$module' and layout_type='DetailView'";
            $getResult = $db->query($check_existance_query);
            $ResultCount = $db->fetchByAssoc($getResult);
            $layoutContent = addslashes($layout);
            if (empty($ResultCount)) {
                //insert new record to db
                $save_detailview_query = "Insert into portal_layout values('$unique_id','$module','DetailView','$layoutContent','$gmtdatetime','$panel_index_seq')";
                $result = $db->query($save_detailview_query);
            } else {
                //update existing record to db
                $save_detailview_query = "Update portal_layout set layout='$layoutContent',modified_date='$gmtdatetime',panel_index='$panel_index_seq'  where module_name='$module' and layout_type='DetailView'";
                $result = $db->query($save_detailview_query);
            }
        }
        // save panel labels to modstrings.
        $parser = new ParserLabel($module);
        $parser->handleSave($panelLable, 'en_us');

        return $result;
        // exit;
    }

    /**
     * Description :: This function is used check duplication of email address in contacts module.
     * 
     * @return array '$message' - message
     */
    function checkExistEmail() {
        // check email id already exist or not for the contact
        global $db;
        $contact_id = $_REQUEST['contact_id'];
        $email_address = $_REQUEST['email_address'];
        $username = $_REQUEST['username'];
        $enable_portal_c = $_REQUEST['enable_portal_c'];
        $password = $_REQUEST['password'];
        $conatct = new Contact();
        $conatct->retrieve($contact_id);
        if ($contact_id == '') {
            $getEmailQuery = " SELECT email_addresses.email_address AS email
                FROM email_addresses
                LEFT JOIN email_addr_bean_rel 
                    ON email_addresses.id = email_addr_bean_rel.email_address_id AND email_addresses.deleted = 0 AND email_addr_bean_rel.deleted = 0
                WHERE email_addresses.email_address = '{$email_address}' AND email_addr_bean_rel.bean_module = 'Contacts'";

            $getUsernameQuery = "SELECT contacts_cstm.username_c AS username
                FROM contacts
                JOIN contacts_cstm ON contacts.id = contacts_cstm.id_c AND contacts.deleted = 0
                WHERE contacts_cstm.username_c = '{$username}'";
        } else {

            $checkExistContactUsingID = " AND email_addr_bean_rel.bean_id != '{$contact_id}'";
            $getEmailQuery = " SELECT email_addresses.email_address AS email
                FROM email_addresses
                LEFT JOIN email_addr_bean_rel ON email_addresses.id = email_addr_bean_rel.email_address_id
                    AND email_addresses.deleted = 0 AND email_addr_bean_rel.deleted = 0
                LEFT JOIN contacts ON contacts.id = email_addr_bean_rel.bean_id
                WHERE email_addresses.email_address = '{$email_address}' {$checkExistContactUsingID} 
                    AND contacts.deleted = 0 AND email_addr_bean_rel.bean_module = 'Contacts'";

            $getUsernameQuery = "SELECT contacts_cstm.username_c AS username
                FROM contacts
                JOIN contacts_cstm ON contacts.id = contacts_cstm.id_c AND contacts.deleted = 0
                WHERE contacts_cstm.username_c = '{$username}' AND contacts.id != '{$contact_id}'";
        }


        $run_Query = $db->query($getEmailQuery);
        $EmailResultQuery = $db->fetchByAssoc($run_Query);
        $username_result = $db->query($getUsernameQuery);
        $UserNameResultQuery = $db->fetchByAssoc($username_result);
        $messages = array();
        if (!empty($EmailResultQuery)) {
            $messages['Email_exist'] = "Email Address is already exist";
        } else {
            $messages['Email_exist'] = '';
            $messages['UserName_exist'] = '';
        }
        if ($enable_portal_c == 'true') {
            if (($contact_id != '' && $conatct->enable_portal_c == '0') || ($contact_id == '')) {
                if (!empty($UserNameResultQuery)) {
                    $messages['UserName_exist'] = "Username is already exist";
                } else {
                    $messages['UserName_exist'] = '';
                }
            }
        }

        return $messages;
    }

    function check_portaluser() {
        global $sugar_version, $sugar_config, $sugar_flavor, $db;

        $contact_id = $_REQUEST['contact_id'];
        $email_address = $_REQUEST['email_address'];
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $first_name = $_REQUEST['first_name'];
        $last_name = $_REQUEST['last_name'];
        $enable_portal_c = $_REQUEST['enable_portal_c'];
        $messages = array();
        $re_sugar_versionForPro75 = '/(7\.5\.[0-9])/';
        $re_sugar_versionForPro76 = '/(7\.6\.[0-9])/';
        $re_sugar_versionForPro77 = '/(7\.7\.[0-9])/';
        if (preg_match($re_sugar_versionForPro75, $sugar_version) || preg_match($re_sugar_versionForPro76, $sugar_version) || preg_match($re_sugar_versionForPro77, $sugar_version)) {

            $sq = "SELECT enable_portal_c
                FROM contacts
                JOIN contacts_cstm ON contacts.id = contacts_cstm.id_c AND contacts.deleted = 0
                WHERE contacts.id = '{$contact_id}'";
            $res = $db->query($sq);
            while ($que_row = $db->fetchByAssoc($res)) {
                $enable_portal_data = $que_row['enable_portal_c'];
            }
            $getUsernameQuery = "SELECT contacts_cstm.username_c AS username
                FROM contacts
                JOIN contacts_cstm ON contacts.id = contacts_cstm.id_c AND contacts.deleted = 0
                WHERE contacts.id = '{$contact_id}'";
            $res = $db->query($getUsernameQuery);
            while ($que_row = $db->fetchByAssoc($res)) {
                $username_data = $que_row['username'];
            }
            $getEmailQuery = "SELECT email_addresses.email_address AS email
                FROM contacts
                JOIN email_addr_bean_rel ON contacts.id = email_addr_bean_rel.bean_id
                    
                JOIN email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id
                WHERE contacts.id = '{$contact_id}' and email_addr_bean_rel.primary_address = '1' and contacts.deleted = '0' and email_addresses.deleted = '0' and email_addr_bean_rel.deleted = '0'";

            $res1 = $db->query($getEmailQuery);
            while ($que_row1 = $db->fetchByAssoc($res1)) {
                $email = $que_row1['email'];
            }
        } else {
            $conatct = new Contact();
            $conatct->retrieve($contact_id);
            $enable_portal_data = ($enable_portal_c) ? 1 : 0;
            $email = $conatct->email1;
            $username_data = $conatct->username_c;
        }
        if ($enable_portal_c == 'true') {
            $query = "SELECT * FROM config WHERE name='wpkey'";
            $result = $db->query($query);
            $row = $db->fetchByAssoc($result);
            $biztech_scp_key = $row['value'];
            require_once('modules/Administration/Administration.php');

            $administrationObj = new Administration();
            $administrationObj->retrieveSettings('PortalPlugin');
            $url = (!empty($administrationObj->settings['PortalPlugin_PortalInstance_url'])) ? $administrationObj->settings['PortalPlugin_PortalInstance_url'] . "/portal-sign-up/" : "";
            if (($contact_id == '') || ($contact_id != '' && $enable_portal_data == '0')) {
                $add_data = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'user_email' => $email_address,
                    'biztech_scp_key' => $biztech_scp_key,
                    'sugar_side_portal_email' => '1'
                );
            } else if (($contact_id != '' && $enable_portal_data == '1')) {
                $add_data = array(
                    'id' => $contact_id,
                    'user_login' => $username,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'user_email' => $email_address,
                    'biztech_scp_key' => $biztech_scp_key,
                    'sugar_side_portal_email' => '1'
                );
            }

            $is_secured = 0;

            $secured = explode('://', $url);
            if ($secured[0] == 'https') {
                $is_secured = 1;
            }

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, $is_secured);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $add_data);
            $result = curl_exec($curl_request);
            curl_close($curl_request);
            if (($contact_id == '') || ($contact_id != '' && $enable_portal_data == '0')) {
                if (($result == 'Username and Email Address already exists.')) {
                    $messages['Email_portal_exists'] = "Email Address is already exist in Portal";
                    $messages['Username_portal_exists'] = "Username is already exist in Portal";
                } else if (($result == 'Username already exists.')) {
                    $messages['Username_portal_exists'] = "Username is already exist in Portal";
                    $messages['Email_portal_exists'] = "";
                } else if (($result == 'Email Address already exists.')) {
                    $messages['Email_portal_exists'] = "Email Address is already exist in Portal";
                    $messages['Username_portal_exists'] = "";
                } else {
                    $messages['Email_portal_exists'] = '';
                    $messages['Username_portal_exists'] = '';
                }
            } else if (($contact_id != '' && $enable_portal_data == '1') && ($email != $email_address)) {
                if ($result == 'Email Address already exists.') {
                    $messages['Email_portal_exists'] = "Email Address is already exist in Portal";
                    $messages['Username_portal_exists'] = '';
                } else {
                    $messages['Email_portal_exists'] = '';
                    $messages['Username_portal_exists'] = '';
                }
            }
        }

        return $messages;
    }

    /**
     * Description :: This function is used to check license validation.
     * 
     * @return bool '$result' - 1 - licence validated
     *                          0 - license not validated
     */
    function validateLicence() {
        // get validate license status
        $key = $_REQUEST['k'];
        $CheckResult = Portalutils::checkPluginLicense($key);
        return $CheckResult;
    }

    /**
     * Description :: This function is used to enable or disable plugin.
     * 
     * @return bool '$result' - true - plugin enabled
     */
    function enableDisableExtension() {
        //used to enable/disable plugin
        require_once('modules/Administration/Administration.php');
        require_once 'custom/biz/function/default_portal_module.php';
        global $db, $sugar_version;
        $enabled = $_REQUEST['enabled'];
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('PortalPlugin');
        $result = array();
        $re_sugar_versionCE = '/(6\.5\.[0-9])/';
        $result['allow'] = 'Sugar7';
        if (preg_match($re_sugar_versionCE, $sugar_version)) {
            $result['allow'] = 'Sugar6';
        }
        $result['status'] = false;
        $url = $_REQUEST['url'];
        $portal_selected = $_REQUEST['forntend_framework'];
        switch ($enabled) {
            case '1': //enabled
                $administrationObj->saveSetting("PortalPlugin", "ModuleEnabled", 1);
                $administrationObj->saveSetting("PortalPlugin", "LastValidationMsg", "");
                break;
            case '0': //disabled
                $administrationObj->saveSetting("PortalPlugin", "ModuleEnabled", 0);
                $administrationObj->saveSetting("PortalPlugin", "LastValidationMsg", "This module is disabled, please contact Administrator.");
                break;
            default: //default is disabled
                $administrationObj->saveSetting("PortalPlugin", "ModuleEnabled", 0);
                $administrationObj->saveSetting("PortalPlugin", "LastValidationMsg", "This module is disabled, please contact Administrator.");

                $result['status'] = true;
        }
        if ($enabled) {
            $PortalInstance = $administrationObj->settings['PortalPlugin_PortalInstance'];
            $PortalInstance_url = $administrationObj->settings['PortalPlugin_PortalInstance_url'];
            if (($portal_selected != 'wordPress') || ($portal_selected == 'wordPress' && $PortalInstance_url != $url)) {
                $administrationObj->saveSetting("PortalPlugin", "PortalInstance", $portal_selected);
                $administrationObj->saveSetting("PortalPlugin", "PortalInstance_url", $url);
                $bc_user_group = new bc_user_group();
                $bc_user_group->retrieve_by_string_fields(array('name' => 'Default'));
                $user_accessible_modules = get_modules();
                foreach ($user_accessible_modules as $key => $value) {
                    $user_accessible_modules[$key] = '^' . $key . '^';
                }
                $modules = implode(',', $user_accessible_modules);
                $query = "UPDATE bc_user_group SET is_portal_accessible_group='0'";
                $db->query($query);
                $bc_user_group->is_portal_accessible_group = 1;
                $bc_user_group->accessible_modules = $modules;
                $bc_user_group->save();
                $result['status'] = true;
            }
        }
        json_encode($result);
        return $result;
    }

    /**
     * Description :: This function is used to set WP url.
     * 
     * @return bool '$result' - 1 - WP url set
     *                          0 - WP url not set
     */
    function setUrl() { // set wp url if its already provided
        require_once 'custom/biz/function/default_portal_module.php';
        global $db;
        $url = $_REQUEST['k'];
        $url_select = $_REQUEST['portal_selected'];

        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('PortalPlugin');

        $administrationObj->saveSetting("PortalPlugin", "PortalInstance", $url_select);
        $administrationObj->saveSetting("PortalPlugin", "PortalInstance_url", $url);

        $Framework = $administrationObj->settings['PortalPlugin_PortalInstance'];
        if ($Framework != $url_select) {
            $bc_user_group = new bc_user_group();
            $bc_user_group->retrieve_by_string_fields(array('name' => 'Default'));
            $user_accessible_modules = get_modules();
            foreach ($user_accessible_modules as $key => $value) {
                $user_accessible_modules[$key] = '^' . $key . '^';
            }
            $modules = implode(',', $user_accessible_modules);
            $query = "UPDATE bc_user_group SET is_portal_accessible_group='0'";
            $db->query($query);
            $bc_user_group->is_portal_accessible_group = 1;
            $bc_user_group->accessible_modules = $modules;
            $bc_user_group->save();
            $result = true;
        }


        return $result;
    }

    function validfile() {

        $status = $_REQUEST['status'];
        $amount_module = $_REQUEST['amount_module'];
        $amount_fields = $_REQUEST['amount_fields'];
        $payment_module = $_REQUEST['payment_module'];
        $payment_feilds = $_REQUEST['payment_feilds'];

        $oAdministration = new Administration();
        $oAdministration->saveSetting("payment", "status", $status);
        $oAdministration->saveSetting("payment", "amount_module", $amount_module);
        $oAdministration->saveSetting("payment", "amount_feilds", $amount_fields);
        $oAdministration->saveSetting("payment", "payment_module", $payment_module);
        $oAdministration->saveSetting("payment", "payment_feilds", $payment_feilds);
    }

    function getpaymentmoduleFields() {
        global $mod_strings;
        $module_fileds = $_POST['module_fileds'];
        $currency_fields = $_POST['currency_fields'];
        $status_fields = $_POST['status_fields'];

        if ($module_fileds == "") {
            ob_clean();
            echo $module_fileds = "false";
            exit();
        } else {
            $moduleClass = BeanFactory::getBeanName($module_fileds);
            $focus = new $moduleClass();

            if ($currency_fields != '') {
                foreach ($focus->field_defs as $key => $field_def) {

                    $GLOBALS['log']->fatal("Fields " . print_r($field_def, 1));
                    if ($field_def['type'] == 'currency') {
                        // valid def found, process
                        $collectionKey[] = array("name" => $key, "value" => translate($field_def['vname'], $module_fileds));
                    }
                }
            }
            if ($status_fields != '') {
                foreach ($focus->field_defs as $key => $field_def) {
                    if ($field_def['name'] == 'status') {
                        $collectionKey[] = array("name" => $key, "value" => translate($field_def['vname'], $module_fileds));
                    }
                }
            }

            ob_clean();
            echo json_encode($collectionKey);
            exit();
        }
    }

    function CheckPortalAccesibleGroup() {
        global $db;
        $is_check_portal_group = $_REQUEST['portal_accessible_group'];
        $group_id = $_REQUEST['group_id'];
        $group_name = $_REQUEST['group_name'];
        $message = array();
        $query = "SELECT id,is_portal_accessible_group,name FROM bc_user_group where deleted=0";
        $result = $db->query($query);
        $groupArry = array();
        $flag = true;
        while ($row = $db->fetchByAssoc($result)) {
            $groupArry[$row['id']] = $row['is_portal_accessible_group'];
            if (($row['name'] == 'Default')) {
                $group_default_id = $row['id'];
            }
        }
        if ($group_default_id != $group_id && $group_name == 'Default') {
            $message['group_name'] = 'This group name is already set for "Default" group.';
            $flag = false;
        }
        if ($is_check_portal_group == 'true' && $flag) {
            $query = "UPDATE bc_user_group SET is_portal_accessible_group='0'";
            $db->query($query);
        } else {
            if ((in_array('1', $groupArry) && $groupArry[$group_id] == '1') || (!in_array('1', $groupArry) && $groupArry[$group_id] == '0')) {
                $message['Portal_group'] = 'You must have atleast single group.';
            }
        }
        return $message;
    }

    function savemoduleaccesslevel() {
        $userID = $_REQUEST['userID'];
        $selectedModulesString = json_decode(html_entity_decode($_REQUEST['setting']));
        $bc_user_group = new bc_user_group();
        $bc_user_group->retrieve($userID);
        $fullaccessnonArray = array("AOS_Contracts", "AOS_Invoices", "AOK_KnowledgeBase", "AOS_Quotes", "Calls", "Meetings");

        $accessible_modules_array = array();
        foreach ($selectedModulesString as $module_name => $actions) {
            if (in_array($module_name, $fullaccessnonArray)) {
                $selectedModulesString->$module_name = array();
            }
            $accessible_modules_array[] = $module_name;
        }
        $new_array_module = json_encode($selectedModulesString);
        $bc_user_group->accessible_modules = encodeMultienumValue($accessible_modules_array);
        $bc_user_group->portal_accessiable_module = $new_array_module;
        $bc_user_group->save();
    }

    function portalContactsExport() {
        global $db;
        require_once 'data/SugarBean.php';
        $recordIDS = array();
        $contacts = new Contact();
        $result = $contacts->get_list("", "enable_portal_c = '1'");
        foreach ($result['list'] as $key => $value) {
            if ($value->enable_portal_c) {
                array_push($recordIDS, $value->id);
            }
        }

        return implode(',', $recordIDS);
    }

    function generalConfiguration() {
        $chatEnable = $_REQUEST['chatEnable'];
        $gmailUsername = $_REQUEST['gmailUsername'];
        $gmailMailbox = $_REQUEST['gmailMailbox'];
        $gmailPassword = $_REQUEST['gmailPassword'];
        $twkkey = $_REQUEST['twkkey'];
        $registernewuser = $_REQUEST['registernewuser'];
        $forgotpasswordemail = $_REQUEST['forgotpasswordemail'];
        $array_chart = array();
        if($_REQUEST['case_module_check']){
            $array_chart['case'] = $_REQUEST['case_module_check'];
        }
         if($_REQUEST['invoice_module_check']){
            $array_chart['invoice'] = $_REQUEST['invoice_module_check'];
        }
        if($_REQUEST['quotes_module_check']){
            $array_chart['quotes'] = $_REQUEST['quotes_module_check'];
        }
        $chart_config = json_encode($array_chart);
        $administrationObj = new Administration();
        if ($chatEnable == 'true') {
            $administrationObj->saveSetting("PortalPlugin", "ChatEnable", '1');
        } else {
            $administrationObj->saveSetting("PortalPlugin", "ChatEnable", '0');
        }
        $administrationObj->saveSetting("PortalPlugin", "GmailUsername", $gmailUsername);
        $administrationObj->saveSetting("PortalPlugin", "GmailMailbox", $gmailMailbox);
        $administrationObj->saveSetting("PortalPlugin", "GmailPassword", $gmailPassword);
        $administrationObj->saveSetting("PortalPlugin", "TalkApikey", $twkkey);
        $administrationObj->saveSetting("PortalPlugin", "NewRegisterUserEmail", $registernewuser);
        $administrationObj->saveSetting("PortalPlugin", "ForgotPasswordEmail", $forgotpasswordemail);
        $administrationObj->saveSetting("PortalPlugin", "PortalChart", $chart_config);


        return true;
    }

    function gmailMailbox() {

        global $db;
        $gmailUsername = $_REQUEST['gmailUsername'];
        $inboundEmail = "SELECT mailbox FROM inbound_email WHERE email_user='{$gmailUsername}' AND deleted = '0'";
        $resultOfInboundEmail = $db->query($inboundEmail);

        $gmailmailbox = array();
        $html = "<option value='' selected> Select Mailbox</option>";
        while ($row = $db->fetchByAssoc($resultOfInboundEmail)) {
            $mailbox = explode(',', $row['mailbox']);
            foreach ($mailbox as $value) {
                $gmailmailbox[$value] = $value;
            }
        }
        foreach ($gmailmailbox as $key) {
            $html .= "<option value='{$key}'>{$key}</option>";
        }

        ob_clean();
        echo $html;
        exit();
    }

//    function saveEmailconfig(){
//        $oAdministration = new Administration();
//        $oAdministration->saveSetting("PortalPlugin", "NewRegisterUserEmail",$_REQUEST['registernewuser']);
//        $oAdministration->saveSetting("PortalPlugin", "ForgotPasswordEmail", $_REQUEST['forgotpasswordemail']);
//        return true;
//    }
}
