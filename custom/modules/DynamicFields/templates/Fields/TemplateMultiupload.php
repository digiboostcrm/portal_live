<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('modules/DynamicFields/templates/Fields/TemplateField.php');

class TemplateMultiupload extends TemplateField {
    //var $type = 'text';
    var $type='multiupload';
    function get_field_def(){
        $def = parent::get_field_def();
        $def['dbType'] = 'text';
        return $def;
    }
}
?>