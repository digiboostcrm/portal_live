<?php
// created: 2018-08-29 21:52:20
$viewdefs['AOS_Products']['QuickCreate'] = array (
  'templateMeta' => 
  array (
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
    'form' => 
    array (
      'enctype' => 'multipart/form-data',
      'headerTpl' => 'modules/AOS_Products/tpls/EditViewHeader.tpl',
    ),
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'modules/AOS_Products/js/products.js',
      ),
    ),
    'useTabs' => false,
    'tabDefs' => 
    array (
      'DEFAULT' => 
      array (
        'newTab' => false,
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
          'name' => 'url',
          'label' => 'LBL_URL',
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
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'label' => 'LBL_DESCRIPTION',
        ),
      ),
      6 => 
      array (
        0 => 
        array (
          'name' => 'product_image',
          'customCode' => '{$PRODUCT_IMAGE}',
        ),
      ),
    ),
  ),
);