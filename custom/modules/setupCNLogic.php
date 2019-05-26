<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class setupCNLogic
{

    function setAssignedUserId(&$bean){
         if (!$bean->is_save && !isset($bean->is_save)) {
            if (isset($bean->fetched_row['assigned_user_id'])) {
                $bean->assign_id = $bean->fetched_row['assigned_user_id'];
            } else {
                $bean->assign_id = ' ';
            }

        }
    }

    function queueNotification(&$bean, $event, $arguments)
    {
        require_once "modules/bc_Notification/bc_Notification.php";
        if (($bean->object_name != "UpgradeHistory") /* && empty($_REQUEST['to_pdf']) */ && (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv'])) {
        require_once 'custom/include/custom_notification_functions.php';
        global $current_user, $db;
        $user = new User();
        $user->retrieve($bean->assigned_user_id);
        $modules = getUNModulepreference($user->id);
        $module_names = array_keys($modules);

        // get records from notification_modules table
        $saved_modules = array();
        $query = "SELECT * FROM notification_modules";
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            array_push($saved_modules, $row['module']);
        }
        if (in_array($bean->module_dir, $module_names) && in_array($bean->module_dir, $saved_modules)) {
            if (isset($bean->assign_id) && ($bean->assign_id != $bean->assigned_user_id) && ($current_user->id != $bean->assigned_user_id)) {
                $notification = new bc_Notification();
                $notification->name = $bean->name;
                $notification->assigned_user_id = $bean->assigned_user_id;
                $notification->parent_type = $bean->module_dir;
                $notification->parentid = $bean->id;
               // $notification->is_notify = 0;
                $notification->is_save = true;
                $notification->save();
            }
        }
      }
    }

}
