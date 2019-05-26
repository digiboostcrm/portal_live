<?php
 // created: 2017-11-14 16:51:37
$layout_defs["AOS_Product_Categories"]["subpanel_setup"]['leads_aos_product_categories_1'] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_AOS_PRODUCT_CATEGORIES_1_FROM_LEADS_TITLE',
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
