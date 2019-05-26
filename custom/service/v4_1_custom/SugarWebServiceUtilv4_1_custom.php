<?php

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


require_once('service/v4/SugarWebServiceUtilv4.php');

class SugarWebServiceUtilv4_1_custom extends SugarWebServiceUtilv4 {

    /**
     * Validate the provided session information is correct and current.  Load the session.
     *
     * @param String $session_id -- The session ID that was returned by a call to login.
     * @return true -- If the session is valid and loaded.
     * @return false -- if the session is not valid.
     */
    function validate_authenticated($session_id) {
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->validate_authenticated');
        if (!empty($session_id)) {

            // only initialize session once in case this method is called multiple times
            if (!session_id()) {
                session_id($session_id);
                session_start();
            }

            if (!empty($_SESSION['is_valid_session']) && $this->is_valid_ip_address('ip_address') && $_SESSION['type'] == 'user') {

                global $current_user;
                require_once('modules/Users/User.php');
                $current_user = new User();
                $current_user->retrieve($_SESSION['user_id']);
                $this->login_success();
                $GLOBALS['log']->info('Begin: SoapHelperWebServices->validate_authenticated - passed');
                $GLOBALS['log']->info('End: SoapHelperWebServices->validate_authenticated');
                return true;
            }

            $GLOBALS['log']->debug("calling destroy");
            session_destroy();
        }
        LogicHook::initialize();
        $GLOBALS['logic_hook']->call_custom_logic('Users', 'login_failed');
        $GLOBALS['log']->info('End: SoapHelperWebServices->validate_authenticated - validation failed');
        return false;
    }

    /**
     * check module access status
     *
     * @param String $user -- The user id
     * @param String $module_name -- name of module for which to check access
     * @param String $action -- check access right for which action default is write
     * @return true -- If the module is accessbile for given action.
     * @return false -- if the module is not accessible for given action.
     */
    function check_modules_access($user, $module_name, $action = 'write') {
        if (!isset($_SESSION['avail_modules'])) {
            $_SESSION['avail_modules'] = get_user_module_list($user);
        }
        if (isset($_SESSION['avail_modules'][$module_name])) {
            if ($action == 'write' && $_SESSION['avail_modules'][$module_name] == 'read_only') {
                if (is_admin($user))
                    return true;
                return false;
            }elseif ($action == 'write' && strcmp(strtolower($module_name), 'users') == 0 && !$user->isAdminForModule($module_name)) {
                //rrs bug: 46000 - If the client is trying to write to the Users module and is not an admin then we need to stop them
                return false;
            }
            return true;
        }
        return false;

    }

    /**
     * getRelationshipResults
     * Returns the
     *
     * @param Mixed $bean The SugarBean instance to retrieve relationships from
     * @param String $link_field_name The name of the relationship entry to fetch relationships for
     * @param Array $link_module_fields Array of fields of relationship entries to return
     * @param string $optional_where String containing an optional WHERE select clause
     * @param string $order_by String containing field to order results by
     * @param Number $offset -- where to start in the return (defaults to 0)
     * @param Number $limit -- number of results to return (defaults to all)
     * @return array|bool Returns an Array of relationship results; false if relationship could not be retrieved
     */
    function getRelationshipResults($bean, $link_field_name, $link_module_fields, $optional_where = '', $order_by = '', $offset = 0, $limit = '') {
        $GLOBALS['log']->info('Begin: SoapHelperWebServices->getRelationshipResults');
        require_once('include/TimeDate.php');
        global $beanList, $beanFiles, $current_user;
        global $disable_date_format, $timedate;

        $bean->load_relationship($link_field_name);

        if (isset($bean->$link_field_name)) {
            //First get all the related beans
            $params = array();
            $params['offset'] = $offset;
            $params['limit'] = $limit;
            $params['order_by'] = $order_by;
            if (!empty($optional_where)) {
                $params['where'] = $optional_where;
            }

            $related_beans = $bean->$link_field_name->getBeans($params);
            //Create a list of field/value rows based on $link_module_fields
            $list = array();
            $filterFields = array();
            if (!empty($order_by) && !empty($related_beans)) {
                // $related_beans = order_beans($related_beans, $order_by);
                $related_beans = custom_order_beans($related_beans, $order_by);
            }
            foreach ($related_beans as $id => $bean) {
                if (empty($filterFields) && !empty($link_module_fields)) {
                    $filterFields = $this->filter_fields($bean, $link_module_fields);
                }
                $row = array();
                foreach ($filterFields as $field) {
                    if (isset($bean->$field)) {
                        if (isset($bean->field_defs[$field]['type']) && $bean->field_defs[$field]['type'] == 'date') {
                            $row[$field] = $timedate->to_display_date_time($bean->$field);
                        } else {
                            $row[$field] = $bean->$field;
                        }
                    } else {
                        $row[$field] = "";
                    }
                }
                //Users can't see other user's hashes
                if (is_a($bean, 'User') && $current_user->id != $bean->id && isset($row['user_hash'])) {
                    $row['user_hash'] = "";
                }
                $row = clean_sensitive_data($bean->field_defs, $row);
                $list[] = $row;
            }
            $GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipResults');
            return array('rows' => $list, 'fields_set_on_rows' => $filterFields);
        } else {
            $GLOBALS['log']->info('End: SoapHelperWebServices->getRelationshipResults - ' . $link_field_name . ' relationship does not exists');
            return false;
        } // else
    }

// fix order by issue

    function get_data_list($seed, $order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $favorites = false)
	{
		$GLOBALS['log']->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted']))
		{
			$show_deleted = 1;
		}
		// Fix bug with sort order in get_entry_list
		// $order_by=$seed->process_order_by($order_by, null);

		$params = array();
		if(!empty($favorites)) {
		  $params['favorites'] = true;
		}

		$query = $seed->create_new_list_query($order_by, $where,array(),$params, $show_deleted);
		if($order_by)
			$query .= " ORDER BY $order_by";
		
		return $seed->process_list_query($query, $row_offset, $limit, $max, $where);
	}



}