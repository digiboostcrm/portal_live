<?php
 // created: 2018-03-16 15:23:30
$layout_defs["Cases"]["subpanel_setup"]['cases_dg_comments_1'] = array (
  'order' => 100,
  'module' => 'dg_comments',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CASES_DG_COMMENTS_1_FROM_DG_COMMENTS_TITLE',
  'get_subpanel_data' => 'cases_dg_comments_1',
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
