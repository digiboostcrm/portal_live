<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['ut_rightsignature_api_keys'] = array (
    'table' => 'ut_rightsignature_api_keys',
    'fields' => array (
          'id' =>
          array (
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required'=>true,
            'reportable'=>true,
            'comment' => 'Unique identifier'
          ),
          'consumer_key'=>
          array (
            'name'=>'consumer_key',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>100
          ),
          'consumer_secret'=>
          array (
            'name'=>'consumer_secret',
            'vname'=> 'LBL_CONSUMER_SECRET',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>255
          ),
          'deleted' =>
          array (
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'default' => '0',
            'reportable'=>false,
          )
    ),
    'indices' => array (
        array('name' => 'ut_rightsignature_api_keys_pk', 'type' =>'primary', 'fields'=>array('id')),
    )
);
?>