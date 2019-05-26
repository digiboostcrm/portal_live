<?php

function get_comments($params) {
   
   $bean = $GLOBALS['app']->controller->bean;
   //$GLOBALS['log']->fatal(print_r($bean,1));
   $return_array['select'] = " SELECT dg_comments.id";
    $return_array['from'] = " FROM dg_comments ";
    $return_array['join'] = " JOIN cases_dg_comments_1_c as rel ON rel.cases_dg_comments_1dg_comments_idb = dg_comments.id ";
    $return_array['where'] = " WHERE dg_comments.status = 'Public' AND rel.cases_dg_comments_1cases_ida = '$bean->id' AND rel.deleted = 0 AND dg_comments.deleted = 0";
    //$return_array['join_tables'] = '';
    return $return_array;
}
