<?php
// created: 2018-03-16 15:23:30
$dictionary["dg_comments"]["fields"]["cases_dg_comments_1"] = array (
  'name' => 'cases_dg_comments_1',
  'type' => 'link',
  'relationship' => 'cases_dg_comments_1',
  'source' => 'non-db',
  'module' => 'Cases',
  'bean_name' => 'Case',
  'vname' => 'LBL_CASES_DG_COMMENTS_1_FROM_CASES_TITLE',
  'id_name' => 'cases_dg_comments_1cases_ida',
);
$dictionary["dg_comments"]["fields"]["cases_dg_comments_1_name"] = array (
  'name' => 'cases_dg_comments_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CASES_DG_COMMENTS_1_FROM_CASES_TITLE',
  'save' => true,
  'id_name' => 'cases_dg_comments_1cases_ida',
  'link' => 'cases_dg_comments_1',
  'table' => 'cases',
  'module' => 'Cases',
  'rname' => 'name',
);
$dictionary["dg_comments"]["fields"]["cases_dg_comments_1cases_ida"] = array (
  'name' => 'cases_dg_comments_1cases_ida',
  'type' => 'link',
  'relationship' => 'cases_dg_comments_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_CASES_DG_COMMENTS_1_FROM_DG_COMMENTS_TITLE',
);
