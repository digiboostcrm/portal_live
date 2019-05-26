<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['ut_oauth_session'] = array (
    'table' => 'ut_oauth_session',
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
          
          /*
          'id' => array (
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'int',
            'readonly' => true,
            'len' => 10,
            'required' => true,
            'auto_increment' => true,
            'unified_search' => true,
            'comment' => 'Visual unique identifier',
            'duplicate_merge' => 'disabled',
            'disable_num_format' => true,
            'inline_edit' => false,
          ),
          */
          'session'=>
          array (
            'name'=>'session',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>50
          ),
          'state'=>
          array (
            'name'=>'state',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>50
          ),
          'access_token'=>
          array (
            'name'=>'access_token',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'text',
            'rows' => 6,
            'cols' => 80
          ),
          'expiry'=>
          array (
            'name'=>'expiry',
            'vname'=> 'LBL_DATE_EXPIRY',
            'type' => 'datetime',
          ),
          'type'=>
          array (
            'name'=>'type',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>50
          ),
          'server'=>
          array (
            'name'=>'server',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>20
          ),
          'creation'=>
          array (
            'name'=>'creation',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type' => 'datetime'
          ),
          'access_token_secret'=>
          array (
            'name'=>'access_token_secret',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'text',
            'rows' => 6,
            'cols' => 80
          ),
          'authorized'=>
          array (
            'name'=>'authorized',
            'vname'=> 'LBL_CONSUMER_KEY',
            'type'=>'varchar',
            'dbType' => 'varchar',
            'len'=>1
          ),
          'user_fld'=>
          array (
            'name'=>'user_fld',
            'vname'=> 'LBL_CONSUMER_SECRET',
            'type' => 'int',
            //'dbType' => 'double',
            //'dbType' => 'int',
            'len'=>10
          ),
          'refresh_token'=>
          array (
            'name'=>'refresh_token',
            'vname'=> 'LBL_CONSUMER_SECRET',
            'type'=>'text',
            'rows' => 6,
            'cols' => 80
          ),
          'access_token_response'=>
          array (
            'name'=>'access_token_response',
            'vname'=> 'LBL_CONSUMER_SECRET',
            'type'=>'text',
            'rows' => 6,
            'cols' => 80
          )
    ),
    'indices' => array (
        array('name' => 'ut_oauth_session_pk', 'type' =>'primary', 'fields'=>array('id')),
        array('name' => 'ut_oauth_session_index', 'type' =>'unique', 'fields'=>array('session','server')),
    )
    //'indices' => array (
//        array('name' => 'ut_oauth_session_pk', 'type' =>'primary', 'fields'=>array('id')),
//    )
    //'indices'=>array(
//         'number'=>array('name' =>strtolower($module).'numk', 'type' =>'unique', 'fields'=>array($_object_name . '_number'))
//    ),
);
?>