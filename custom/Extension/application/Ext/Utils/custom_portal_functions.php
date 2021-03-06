<?php
/**
 * The file used to add additional utility functions which are used in custom REST API calls. 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */


function custom_cmp_beans($a, $b) {
    global $sugar_web_service_order_by, $checkOrderByOrder;
    //If the order_by field is not valid, return 0;
    if (empty($sugar_web_service_order_by) || !isset($a->$sugar_web_service_order_by) || !isset($b->$sugar_web_service_order_by)) {
        return 0;
    }
    if (is_object($a->$sugar_web_service_order_by) || is_object($b->$sugar_web_service_order_by) || is_array($a->$sugar_web_service_order_by) || is_array($b->$sugar_web_service_order_by)) {
        return 0;
    }
    if ($checkOrderByOrder == 'Asc') {
        if ($a->$sugar_web_service_order_by < $b->$sugar_web_service_order_by) {
            return -1;
        } else {
            return 1;
        }
    } else {
        if ($a->$sugar_web_service_order_by > $b->$sugar_web_service_order_by) {
            return -1;
        } else {
            return 1;
        }
    }
}

function custom_order_beans($beans, $field_name) {
    $GLOBALS['log']->debug('Call custom_orders_bean' . $field_name);
    //Since php 5.2 doesn't include closures, we must use a global to pass the order field to cmp_beans.
    global $sugar_web_service_order_by, $checkOrderByOrder;
    $fieldName = explode(' ', $field_name);

    if (in_array('desc', $fieldName) || in_array('DESC', $fieldName)) {
        $checkOrderByOrder = 'Desc';
    } else {
        $checkOrderByOrder = 'Asc';
    }
    $sugar_web_service_order_by = $fieldName[0];
    usort($beans, "custom_cmp_beans");
    $GLOBALS['log']->fatal('beans are' . print_r($beans, true));
    return $beans;
}
