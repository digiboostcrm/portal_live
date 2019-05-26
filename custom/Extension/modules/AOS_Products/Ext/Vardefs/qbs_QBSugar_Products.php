<?php
$dictionary["AOS_Products"]["fields"]["qbcreatedtime_c"]= array (
		'name' => 'qbcreatedtime_c',
		'vname' => 'LBL_QB_CREATED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["AOS_Products"]["fields"]["qbmodifiedtime_c"]= array (
		'name' => 'qbmodifiedtime_c',
		'vname' => 'LBL_QB_MODIFIED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["AOS_Products"]["fields"]["qbid_c"]= array (
		'name' => 'qbid_c',
		'vname' => 'LBL_QBID',
		'type' => 'varchar',
		'len' => '50',
		);

$dictionary["AOS_Products"]["fields"]["qbincomeaccount_c"] = array (
		'name' => 'qbincomeaccount_c',
		'vname' => 'LBL_QBINCOMEACCOUNT',
		'type' => 'enum',
                'required' => false,
		'function' => 'getQBItemAccounts_qbs_QBSugar',
		); 

$dictionary["AOS_Products"]["fields"]["qbexpenseaccount_c"] = array (
		'name' => 'qbexpenseaccount_c',
		'vname' => 'LBL_QBEXPENSEACCOUNT',
		'type' => 'enum',
		'required' => false,
		'function' => 'getQBItemAccounts_qbs_QBSugar',
		); 
$dictionary["AOS_Products"]["fields"]["qbstartdate_c"] = array(
		'name' => 'qbstartdate_c',
		'vname' => 'Start Date',
		'type' => 'date',
                'len' => '50',
		);
$dictionary["AOS_Products"]["fields"]["qbqtyinhand_c"] = array(
		'name' => 'qbqtyinhand_c',
		'vname' => 'Qty. in Hand',
		'type' => 'decimal(25,3)',
                'len' => '25',
		);
$dictionary["AOS_Products"]["fields"]["qbtype_c"] = array(
		'name' => 'qbtype_c',
		'vname' => 'Type',
		'type' => 'enum',
		'required' => false,
		'function' => 'getQBType_qbs_QBSugar',
		);
$dictionary["AOS_Products"]["fields"]["qbassetaccount_c"] = array(
		'name' => 'qbassetaccount_c',
		'vname' => 'Asset Account',
		'type' => 'enum',
		'required' => false,
		'function' => 'getQBAssetAccount_qbs_QBSugar',
		);

