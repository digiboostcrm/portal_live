<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class custombc_NotificationController extends SugarController
{

    public function action_getnotify()
    {
        require_once 'custom/include/custom_notification_functions.php';
        global $db, $current_user,$app_list_strings,$sugar_config;
        $name = array();
        $modules_name = getUNModulepreference($current_user->id);
        $modules = array_keys($modules_name);
        //Remove outer select query
        if($db->dbType == 'mssql'){
            $sql_query = "SELECT TOP 1
                            *
                        FROM
                            (SELECT
                                n_q.id AS nid,
                                    n_q.parentid AS bean_id,
                                    n_q.parent_type AS bean_type,
                                    n_q.date_entered AS date_time,
                                    users.user_name AS uname
                            FROM
                                bc_notification n_q
                            LEFT JOIN users users ON n_q.assigned_user_id = users.id
                            WHERE
                                n_q.is_notify = '0' AND users.id = '{$current_user->id}'
                           ) AS Notify_data
                        ORDER BY date_time DESC";
        }else{
            $sql_query = "SELECT
                                *
                            FROM
                                (SELECT
                                    n_q.id AS nid,
                                        n_q.parentid AS bean_id,
                                        n_q.parent_type AS bean_type,
                                        n_q.date_entered AS date_time,
                                        users.user_name AS uname
                                FROM
                                    bc_notification n_q
                                LEFT JOIN users users ON n_q.assigned_user_id = users.id
                                WHERE
                                    n_q.is_notify = '0' AND users.id = '{$current_user->id}'
                                ORDER BY date_time DESC) AS Notify_data
                            ORDER BY date_time DESC
                            LIMIT 1";
        }
        $sql = $db->query($sql_query);
        while ($row = $db->fetchByAssoc($sql)) {
            $field_string = $join_string = "";
            $bean_id = $row['bean_id'];
            $bean_type = $row['bean_type'];
            if ($bean_type == "ProspectLists") {
                $bean_type = "Prospect_Lists";
            }
            $table_name = strtolower($bean_type);
            if ($bean_type == "Contacts" || $bean_type == "Leads" || $bean_type == "Prospects") {
                $field_name = "last_name";
            } else {
                $field_name = "name";
            }
            if ($bean_type != 'Users') {
                $field_string = "{$table_name}.{$field_name} AS {$table_name}_name";
                $join_string = " LEFT JOIN {$table_name} ON n_q.parentid = {$table_name}.id ";
            }
            $getName_query = "SELECT {$field_string} FROM bc_notification n_q {$join_string}
                                WHERE n_q.parentid = '{$bean_id}'";
            $getName_result = $db->query($getName_query);
            $row_name = $db->fetchByAssoc($getName_result);
            $name[$bean_id][0] = htmlspecialchars_decode($row_name["{$table_name}_name"],ENT_QUOTES);
            $name[$bean_id][1] = $bean_type;
            $name[$bean_id][2] = $modules_name[$bean_type];
               $name[$bean_id][3] = isset($app_list_strings['moduleListSingular'][$bean_type]) ? $app_list_strings['moduleListSingular'][$bean_type] : $app_list_strings['moduleList'][$bean_type];
            $curr_user = $row['uname'];
            $iconImageUrl = "themes/{$sugar_config['default_theme']}/images/icon_" . $name[$bean_id][1] . ".gif";
            if(file_exists($iconImageUrl)){
                $imageURL = $iconImageUrl;
            }else{
                $imageURL = "themes/{$sugar_config['default_theme']}/images/sugar_icon.png";
            }
            $name = serialize($name);
            $result = $name . "||" . $curr_user."||".$imageURL ."||".$row['nid'];
            echo $result;
        }
        exit;
    }

    function action_updateNotificationRecord(){
        $id = $_REQUEST['record'];
        global $db,$current_user;
        $update_query = "UPDATE bc_notification
                                        SET
                                            is_notify = '1'
                                        WHERE
                                            assigned_user_id = '{$current_user->id}' AND id = '{$id}'
                                        ";
       $db->query($update_query);
       exit;
    }

    public function action_setNotificationModules()
    {
        global $current_user;
        if (is_admin($current_user)) {
            $this->view = "SetNotification";
            $GLOBALS['view'] = $this->view;
        }else{
            $this->view = "noaccess";
            $GLOBALS['view'] = $this->view;
        }
    }

    function action_saveNotificatonModule()
    {
        global $db;
        $modules = $_REQUEST['chkmodule'];
        $date = TimeDate::getInstance()->nowDb();
        $delete_query = "DELETE FROM notification_modules";
        $db->query($delete_query);
        foreach ($modules as $module) {
            $id = create_guid();
            $insert_query = "INSERT INTO notification_modules
                                        (id,
                                         module,
                                         is_active,
                                         date_created)
                            VALUES ('{$id}',
                                    '{$module}',
                                    1,
                                    '{$date}')";
            $db->query($insert_query);
        }
        header("Location: index.php?module=bc_Notification&action=setNotificationModules&msg=success");
        exit;
    }

}

?>
