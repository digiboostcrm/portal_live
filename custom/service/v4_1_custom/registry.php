<?php
/**
 * The file used to register custom functions for REST API 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */

require_once('service/v4_1/registry.php');

class registry_v4_1_custom extends registry_v4_1
{
    protected function registerFunction()
    {
        parent::registerFunction();

        $this->serviceClass->registerFunction(
            'get_customListlayout',
            array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'view'=>'xsd:string'),
            array('return'=>'tns:list_layout'));

        $this->serviceClass->registerFunction(
            'store_wpkey',
            array('session'=>'xsd:string', 'key'=>'xsd:string'),
            array('return'=>'tns:key'));

        $this->serviceClass->registerFunction(
            'getUser_accessibleModules',
            array('session'=>'xsd:string', 'module_name'=>'xsd:string','user_id'=>'xsd:string'),
            array('return'=>'tns:access_right_modules'));

        $this->serviceClass->registerFunction(
            'getUserTimezone',
            array('session'=>'xsd:string', 'module_name'=>'xsd:string','user_id'=>'xsd:string'),
            array('return'=>'tns:timezone'));
        
        $this->serviceClass->registerFunction(
            'getPortal_accessibleModules',
            array('session'=>'xsd:string', 'module_name'=>'xsd:string','user_id'=>'xsd:string'),
            array('return'=>'tns:portal_accessible_modules'));

        $this->serviceClass->registerFunction(
            'IsActivate',
            array(),
            array('return'=>'tns:LicenceDetails'));
        $this->serviceClass->registerFunction(
                'forgotPassword', array('username' => 'xsd:string', 'email' => 'xsd:string'), array('return' => 'tns:ForgotPasswordDetails'));
$this->serviceClass->registerFunction(
                'signupContact', array('session' => 'xsd:string', 'contact_detail' => 'xsd:string'), array('return' => 'xsd:string'));
$this->serviceClass->registerFunction(
                'get_moduleLayouts', array('session' => 'xsd:string', 'module_name' => 'xsd:string'), array('return' => 'tns:string'));
$this->serviceClass->registerFunction(
                'getChatConfiguration', array(), array('return' => 'tns:string'));
 $this->serviceClass->registerFunction(
                'globalSearch', array(), array('session' =>'xsd:string' ,'contact_id' =>'xsd:string','searchDetail'=>'xsd:string', 'return' => 'tns:string')); 
 $this->serviceClass->registerFunction(
                'getChartdetails', array(), array('session' =>'xsd:string' ,'contact_id' =>'xsd:string','searchDetail'=>'xsd:string', 'return' => 'tns:string')); 
 $this->serviceClass->registerFunction(
                'getPortalConnectionMessageList', array(), array('session' =>'xsd:string' ,'contact_id' =>'xsd:string','searchDetail'=>'xsd:string', 'return' => 'tns:string'));
 $this->serviceClass->registerFunction(
                'getPortalLoginMessageList', array(), array('session' =>'xsd:string' ,'contact_id' =>'xsd:string','searchDetail'=>'xsd:string', 'return' => 'tns:string')); 
 $this->serviceClass->registerFunction(
                'getPrimaryAccessibleModules', array(), array('session' =>'xsd:string' ,'contact_id' =>'xsd:string','searchDetail'=>'xsd:string', 'return' => 'tns:string')); 
				
				/* shatty code */
$this->serviceClass->registerFunction(
                'get_option_list',
					array('session'=>'xsd:string', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);
$this->serviceClass->registerFunction(
                'get_comments',
					array('session'=>'xsd:string', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);		
$this->serviceClass->registerFunction(
                'add_comments',
					array('session'=>'xsd:string','module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);
$this->serviceClass->registerFunction(
                'get_ticket_data',
					array('session'=>'xsd:string', 'ticket_id'=>'tns:ticket_id'),
					array('return'=>'tns:string')
				);		
$this->serviceClass->registerFunction(
                'add_ticket',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);					
$this->serviceClass->registerFunction(
                'get_record_list_account',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);								
$this->serviceClass->registerFunction(
                'get_record_list_email',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);
$this->serviceClass->registerFunction(
                'get_record_related_bean',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);	
$this->serviceClass->registerFunction(
                'add_cases_update',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);	
$this->serviceClass->registerFunction(
                'upload_multi_doc',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);	
$this->serviceClass->registerFunction(
                'portal_upload_doc',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);				
				
$this->serviceClass->registerFunction(
                'add_doc_ticket',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);
$this->serviceClass->registerFunction(
                'testing_list',
					array('session'=>'xsd:string', 'module_name'=>'tns:module_name', 'name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);
$this->serviceClass->registerFunction(
                'get_document',
					array('name_value_list'=>'tns:name_value_list'),
					array('return'=>'tns:string')
				);				
    }
}

?>
