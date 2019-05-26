<?php
// created: 2018-08-29 21:52:20
$viewdefs['AOS_Products']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
        0 => 'EDIT',
        1 => 'DUPLICATE',
        2 => 'DELETE',
      ),
    ),
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'useTabs' => true,
    'tabDefs' => 
    array (
      'DEFAULT' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'default' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'name',
          'label' => 'LBL_NAME',
        ),
        1 => 
        array (
          'name' => 'part_number',
          'label' => 'LBL_PART_NUMBER',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'aos_product_category_name',
          'label' => 'LBL_AOS_PRODUCT_CATEGORYS_NAME',
        ),
        1 => 
        array (
          'name' => 'type',
          'label' => 'LBL_TYPE',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'cost',
          'label' => 'LBL_COST',
        ),
        1 => 
        array (
          'name' => 'currency_id',
          'studio' => 'visible',
          'label' => 'LBL_CURRENCY',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'price',
          'label' => 'LBL_PRICE',
        ),
        1 => 
        array (
          'name' => 'one_time_price_c',
          'label' => 'LBL_ONE_TIME_PRICE',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'contact',
          'label' => 'LBL_CONTACT',
        ),
        1 => 
        array (
          'name' => 'url',
          'label' => 'LBL_URL',
        ),
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'label' => 'LBL_DESCRIPTION',
        ),
        1 => 
        array (
          'name' => 'out_sourced',
          'studio' => true,
          'label' => 'LBL_OUT_SOURCED',
        ),
      ),
      6 => 
      array (
        0 => 
        array (
          'name' => 'product_image',
          'label' => 'LBL_PRODUCT_IMAGE',
          'customCode' => '<img src="{$fields.product_image.value}"/>',
        ),
      ),
    ),
    'LBL_SUGARQUICKBOOKS' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'qbcreatedtime_c',
          'label' => 'LBL_QB_CREATED_TIME',
          'type' => 'datetime',
        ),
        1 => 
        array (
          'name' => 'qbmodifiedtime_c',
          'label' => 'LBL_QB_MODIFIED_TIME',
          'type' => 'datetime',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'qbid_c',
          'label' => 'LBL_QBID',
          'type' => 'varchar',
          'len' => '50',
        ),
        1 => 
        array (
          'name' => 'qbincomeaccount_c',
          'label' => 'LBL_QBINCOMEACCOUNT',
          'type' => 'enum',
          'required' => false,
          'function' => 'getQBItemAccounts_qbs_QBSugar',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'qbexpenseaccount_c',
          'label' => 'LBL_QBEXPENSEACCOUNT',
          'type' => 'enum',
          'required' => false,
          'function' => 'getQBItemAccounts_qbs_QBSugar',
        ),
        1 => 
        array (
          'name' => 'qbstartdate_c',
          'label' => 'Start Date',
          'type' => 'date',
          'len' => '50',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'qbqtyinhand_c',
          'label' => 'Qty. in Hand',
          'type' => 'decimal(25,3)',
          'len' => '25',
        ),
        1 => 
        array (
          'name' => 'qbtype_c',
          'label' => 'Type',
          'type' => 'enum',
          'required' => false,
          'function' => 'getQBType_qbs_QBSugar',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'qbassetaccount_c',
          'label' => 'Asset Account',
          'type' => 'enum',
          'required' => false,
          'function' => 'getQBAssetAccount_qbs_QBSugar',
        ),
      ),
    ),
  ),
);