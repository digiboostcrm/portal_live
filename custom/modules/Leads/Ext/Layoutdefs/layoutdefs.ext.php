<?php 
 //WARNING: The contents of this file are auto-generated



$mod_strings['LBL_LEAD_TYPE'] = 'Lead Type';
$mod_strings['LBL_TRACKING_NUMBER'] = 'Tracking Number';


 // created: 2017-11-14 16:52:19
$layout_defs["Leads"]["subpanel_setup"]['leads_aos_products_1'] = array (
  'order' => 100,
  'module' => 'AOS_Products',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_AOS_PRODUCTS_1_FROM_AOS_PRODUCTS_TITLE',
  'get_subpanel_data' => 'leads_aos_products_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2017-11-14 16:51:37
$layout_defs["Leads"]["subpanel_setup"]['leads_aos_product_categories_1'] = array (
  'order' => 100,
  'module' => 'AOS_Product_Categories',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_AOS_PRODUCT_CATEGORIES_1_FROM_AOS_PRODUCT_CATEGORIES_TITLE',
  'get_subpanel_data' => 'leads_aos_product_categories_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);

?>