<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

function setUNModulepreference($user_id, $module_preference)
{
    global $db;
    $module_details = JSON::encode($module_preference);
    $db_Userdetails = getUNModulepreference($user_id);
    $modules = array_keys($db_Userdetails);
    if (is_array($modules)) {
        $delete_query = "DELETE FROM notification_user_preferences WHERE user_id = '{$user_id}'";
        $db->query($delete_query);
    }
    $query = "INSERT INTO notification_user_preferences(user_id,module_preference)
                  values('{$user_id}','{$module_details}')";

    $db->query($query);
}

function getUNModulepreference($user_id)
{
    global $db;
    $notification_preference = array();
    $query = "SELECT * FROM notification_user_preferences WHERE user_id = '{$user_id}'";
    $result = $db->query($query);
    $notification_preference = array();
    while ($row = $db->fetchByAssoc($result)) {
        $notification_preference[$row['user_id']] = JSON::decode(html_entity_decode($row['module_preference']));
    }
    return (!empty($notification_preference[$user_id])) ? $notification_preference[$user_id] : array();
}

?>