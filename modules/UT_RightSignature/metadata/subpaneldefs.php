<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$layout_defs['UT_RightSignature'] = array(
    'subpanel_setup' => array(
        'ut_rightsignature_ut_rsactivities' => array(
          'order' => 100,
          'module' => 'UT_RSActivities',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_UT_RSACTIVITIES_FROM_UT_RSACTIVITIES_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_ut_rsactivities',
          'top_buttons' => 
          array ( ),
        ),
        'ut_signers_ut_rightsignature' => array(
          'order' => 110,
          'module' => 'UT_Signers',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_SIGNERS_UT_RIGHTSIGNATURE_FROM_UT_SIGNERS_TITLE',
          'get_subpanel_data' => 'ut_signers_ut_rightsignature',
          'top_buttons' => array (),
        ),
        
        'ut_rightsignature_accounts_1' => array(
          'order' => 100,
          'module' => 'Accounts',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_accounts_1',
          'top_buttons' => 
          array ( ),
        ),
         'ut_rightsignature_contacts_1' => array(
          'order' => 100,
          'module' => 'Contacts',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_CONTACTS_1_FROM_CONTACTS_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_contacts_1',
          'top_buttons' => 
          array ( ),
        ),
        'ut_rightsignature_leads_1' => array(
          'order' => 100,
          'module' => 'Leads',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_LEADS_1_FROM_LEADS_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_leads_1',
          'top_buttons' => 
          array ( ),
        ),
        'ut_rightsignature_prospects_1' => array(
          'order' => 100,
          'module' => 'Prospects',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_PROSPECTS_1_FROM_PROSPECTS_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_prospects_1',
          'top_buttons' => 
          array ( ),
        ),
        'ut_rightsignature_aos_contracts_1' => array(
          'order' => 100,
          'module' => 'AOS_Contracts',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_AOS_CONTRACTS_1_FROM_AOS_CONTRACTS_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_aos_contracts_1',
          'top_buttons' => 
          array ( ),
        ),
        'ut_rightsignature_aos_quotes_1' => array(
          'order' => 100,
          'module' => 'AOS_Quotes',
          'subpanel_name' => 'default',
          'sort_order' => 'asc',
          'sort_by' => 'id',
          'title_key' => 'LBL_UT_RIGHTSIGNATURE_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE',
          'get_subpanel_data' => 'ut_rightsignature_aos_quotes_1',
          'top_buttons' => 
          array ( ),
        ),
       
    ),
);
