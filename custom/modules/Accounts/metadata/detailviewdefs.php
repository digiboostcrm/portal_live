<?php
$viewdefs ['Accounts'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
          'AOS_GENLET' => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_PRINT_AS_PDF}">',
          ),
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Accounts/Account.js',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_ACCOUNT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_account_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'account_status_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNT_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'comment' => 'Name of the Company',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'account_number_c',
            'label' => 'LBL_ACCOUNT_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'phone_office',
            'comment' => 'The office phone number',
            'label' => 'LBL_PHONE_OFFICE',
          ),
          1 => 
          array (
            'name' => 'website',
            'type' => 'link',
            'label' => 'LBL_WEBSITE',
            'displayParams' => 
            array (
              'link_target' => '_blank',
            ),
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'studio' => 'false',
            'label' => 'LBL_EMAIL',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'label' => 'LBL_BILLING_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
            ),
          ),
          1 => 
          array (
            'name' => 'shipping_address_street',
            'label' => 'LBL_SHIPPING_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
            ),
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'account_manager_c',
            'studio' => 'visible',
            'label' => 'LBL_ACCOUNT_MANAGER',
          ),
        ),
        7 => 
        array (
          0 => '',
        ),
      ),
	  /*
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'content_files_c',
            'label' => 'LBL_CONTENT_FILES',
          ),
          1 => 
          array (
            'name' => 'created_from_c',
            'studio' => 'visible',
            'label' => 'LBL_CREATED_FROM',
          ),
        ),
      ),
	  
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'facebook_username_c',
            'label' => 'LBL_FACEBOOK_USERNAME',
          ),
          1 => 
          array (
            'name' => 'facebook_password_c',
            'label' => 'LBL_FACEBOOK_PASSWORD',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'instagram_username_c',
            'label' => 'LBL_INSTAGRAM_USERNAME',
          ),
          1 => 
          array (
            'name' => 'instagram_password_c',
            'label' => 'LBL_INSTAGRAM_PASSWORD',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'twitter_username_c',
            'label' => 'LBL_TWITTER_USERNAME',
          ),
          1 => 
          array (
            'name' => 'twitter_password_c',
            'label' => 'LBL_TWITTER_PASSWORD',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'linkedin_username_c',
            'label' => 'LBL_LINKEDIN_USERNAME',
          ),
          1 => 
          array (
            'name' => 'linkedin_password_c',
            'label' => 'LBL_LINKEDIN_PASSWORD',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'pinterest_password',
            'studio' => true,
            'label' => 'LBL_PINTREST_PASSWORD',
          ),
          1 => 
          array (
            'name' => 'pinterest_number',
            'studio' => true,
            'label' => 'LBL_PINTREST_NUMBER',
          ),
        ),
      ),
	  */
      'LBL_PANEL_ADVANCED' => 
      array (
		11 => 
		array(
          0 => 'marketing_project_domain',
		),	  
        0 => 
        array (
          0 => 
          array (
            'name' => 'account_type',
            'comment' => 'The Company is of this type',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'industry',
            'comment' => 'The company belongs in this industry',
            'label' => 'LBL_INDUSTRY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'annual_revenue',
            'comment' => 'Annual revenue for this company',
            'label' => 'LBL_ANNUAL_REVENUE',
          ),
          1 => 
          array (
            'name' => 'employees',
            'comment' => 'Number of employees, varchar to accomodate for both number (100) or range (50-100)',
            'label' => 'LBL_EMPLOYEES',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'parent_name',
            'label' => 'LBL_MEMBER_OF',
          ),
        ),
        3 => 
        array (
          0 => 'campaign_name',
        ),
      ),
	  /*
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'billing_day_c',
            'studio' => 'visible',
            'label' => 'LBL_BILLING_DAY',
          ),
          1 => 
          array (
            'name' => 'billing_frequecy',
            'studio' => true,
            'label' => 'LBL_BILLING_FREQUECY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'name_on_bank_acct_c',
            'label' => 'LBL_NAME_ON_BANK_ACCT',
          ),
          1 => 
          array (
            'name' => 'credit_card_name',
            'label' => 'LBL_CREDIT_CARD_NAME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'bank_name',
            'label' => 'LBL_BANK_NAME',
          ),
          1 => 
          array (
            'name' => 'credit_card_number',
            'label' => 'LBL_CREDIT_CARD_NUMBER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'bank_acc_number',
            'label' => 'LBL_BANK_ACC_NUMBER',
          ),
          1 => 
          array (
            'name' => 'credit_card_expire',
            'label' => 'LBL_CREDIT_CARD_EXPIRE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'bank_routing',
            'label' => 'LBL_BANK_ROUTING',
          ),
          1 => 
          array (
            'name' => 'ccv_number',
            'label' => 'LBL_CREDIT_CARD_CCV',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'bank_checking',
            'label' => 'LBL_BANK_CHECKING',
          ),
          1 => 
          array (
            'name' => 'credit_card_master',
            'label' => 'LBL_CREDIT_CARD_MASTER',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'bank_saving',
            'label' => 'LBL_BANK_SAVING',
          ),
          1 => 
          array (
            'name' => 'credit_card_visa',
            'label' => 'LBL_CREDIT_CARD_VISA',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'bank_city',
            'label' => 'LBL_BANK_CITY',
          ),
          1 => 
          array (
            'name' => 'credit_card_amex',
            'label' => 'LBL_CREDIT_CARD_AMEX',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'on_boarding',
            'label' => 'LBL_ON_BOARDING',
          ),
          1 => 
          array (
            'name' => 'credit_card_discover',
            'label' => 'LBL_CREDIT_CARD_DISCOVER',
          ),
        ),
      ),
	  */
    ),
  ),
);
;
?>
