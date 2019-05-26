<?php
$viewdefs ['Opportunities'] = 
array (
  'EditView' => 
  array (
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
      'javascript' => '{$PROBABILITY_SCRIPT}',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL4' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
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
          ),
          1 => 
          array (
            'name' => 'contact_lead_c',
            'studio' => 'visible',
            'label' => 'LBL_CONTACT_LEAD',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sdr',
            'studio' => true,
            'label' => 'LBL_SDR',
          ),
          1 => 'assigned_user_name',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'contract_date_c',
            'label' => 'LBL_CONTRACT_DATE',
          ),
          1 => 'opportunity_type',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'date_closed',
          ),
          1 => 
          array (
            'name' => 'online_date_c',
            'label' => 'LBL_ONLINE_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'free_time_c',
            'label' => 'LBL_FREE_TIME',
          ),
          1 => 
          array (
            'name' => 'contract_term_c',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_TERM',
          ),
        ),
        5 => 
        array (
          0 => 'sales_stage',
          1 => 'lead_source',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'industry_c',
            'studio' => 'visible',
            'label' => 'LBL_INDUSTRY',
          ),
          1 => 'campaign_name',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'project_status',
            'studio' => true,
            'label' => 'LBL_PROJECT_STATUS',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 'next_step',
          1 => 'probability',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'account_panel_show',
            'studio' => true,
            'label' => 'LBL_ACCOUNT_SHOW',
            'customCode' => '<input type="checkbox" onclick="showHide(\'account_panel_show\')" id="account_panel_show" name="account_panel_show">',
          ),
          1 => 'account_name',
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'lead_account_name',
            'studio' => true,
            'label' => 'LBL_ACCOUNT_NAME',
          ),
          1 => 'account_type',
        ),
        1 => 
        array (
          0 => 'account_phone',
          1 => 'account_email',
        ),
        2 => 
        array (
          0 => 'account_description',
          1 => '',
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'logo_design',
          ),
          1 => 
          array (
            'name' => 'website_design_amount_c',
            'label' => 'LBL_WEBSITE_DESIGN_AMOUNT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'social_engagement_c',
            'label' => 'LBL_SOCIAL_ENGAGEMENT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'email_mrr_c',
            'label' => 'LBL_EMAIL_MRR',
          ),
          1 => 
          array (
            'name' => 'hosting_mrr_c',
            'label' => 'LBL_HOSTING_MRR',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'payment_notes_c',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_NOTES',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'setup_fee_c',
            'label' => 'LBL_SETUP_FEE',
          ),
        ),
        5 => 
        array (
          0 => 'description',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'website_design_description_c',
            'studio' => 'visible',
            'label' => 'LBL_WEBSITE_DESIGN_DESCRIPTION_C',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'totalCountRow12',
            'customCode' => '<input type="hidden" name="editRowsCount" id="editRowsCount" value="{$rowCountEdit}">
								<input type="hidden" name="editViewData" id="editViewData" value="{$dataRowEdit}">
								<input type="hidden" name="totalCountRow" id="totalCountRow" value="{$rowCountEdit}">
								<input type="hidden" name="outSourceDom" id="outSourceDom" value="{$outSourceDom}">
								<input type="hidden" name="rowData" id="rowData" value="{$dataRowEdit}">',
          ),
        ),
      ),
      'lbl_editview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'totalCountRow',
            'studio' => true,
            'label' => 'LBL_ROW_COUNT',
          ),
          1 => 
          array (
            'name' => 'term_c',
            'label' => 'LBL_TERM',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'account_manager_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNT_MANAGER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'churned_c',
            'label' => 'LBL_CHURNED',
          ),
          1 => 
          array (
            'name' => 'on_boarding_incomplete_c',
            'label' => 'LBL_ON_BOARDING_INCOMPLETE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'on_boarding_incomplete_notes_c',
            'label' => 'LBL_ON_BOARDING_INCOMPLETE_NOTES',
          ),
          1 => 
          array (
            'name' => 'custom_lead_id',
          ),
        ),
      ),
    ),
  ),
);
$viewdefs['Opportunities']['EditView']['templateMeta'] = array (
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
  'javascript' => '{$PROBABILITY_SCRIPT}',
);
?>
