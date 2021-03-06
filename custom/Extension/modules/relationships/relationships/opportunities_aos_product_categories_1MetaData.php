<?php
// created: 2017-11-14 16:53:31
$dictionary["opportunities_aos_product_categories_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'opportunities_aos_product_categories_1' => 
    array (
      'lhs_module' => 'Opportunities',
      'lhs_table' => 'opportunities',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Product_Categories',
      'rhs_table' => 'aos_product_categories',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'opportunities_aos_product_categories_1_c',
      'join_key_lhs' => 'opportunities_aos_product_categories_1opportunities_ida',
      'join_key_rhs' => 'opportunities_aos_product_categories_1aos_product_categories_idb',
    ),
  ),
  'table' => 'opportunities_aos_product_categories_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'opportunities_aos_product_categories_1opportunities_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'opportunities_aos_product_categories_1aos_product_categories_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'opportunities_aos_product_categories_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'opportunities_aos_product_categories_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'opportunities_aos_product_categories_1opportunities_ida',
        1 => 'opportunities_aos_product_categories_1aos_product_categories_idb',
      ),
    ),
  ),
);