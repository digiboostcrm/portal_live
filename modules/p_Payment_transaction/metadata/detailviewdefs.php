<?php
$module_name = 'p_Payment_transaction';
$_object_name = 'p_payment_transaction';
$viewdefs [$module_name] = 
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_ACCOUNT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_ADDRESS_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EMAIL_ADDRESSES' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DESCRIPTION_INFORMATION' => 
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
          0 => 'name',
          1 => 'phone_office',
        ),
        1 => 
        array (
          0 => 'website',
          1 => 'phone_fax',
        ),
        2 => 
        array (
          0 => 'ticker_symbol',
          1 => 'phone_alternate',
        ),
        3 => 
        array (
          0 => 'rating',
          1 => 'employees',
        ),
        4 => 
        array (
          0 => 'ownership',
          1 => 'industry',
        ),
        5 => 
        array (
          0 => 'p_payment_transaction_type',
          1 => 'annual_revenue',
        ),
        6 => 
        array (
          0 => 'assigned_user_name',
        ),
      ),
      'lbl_address_information' => 
      array (
        0 => 
        array (
          0 => 'billing_address_street',
          1 => 'shipping_address_street',
        ),
      ),
      'lbl_email_addresses' => 
      array (
        0 => 
        array (
          0 => 'email1',
        ),
      ),
      'lbl_description_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'transaction_id',
            'label' => 'LBL_TRANSACTION_ID',
          ),
          1 => 
          array (
            'name' => 'customer_name',
            'label' => 'LBL_CUSTOMER_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'payment_method',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_METHOD',
          ),
          1 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'transaction_detail',
            'studio' => 'visible',
            'label' => 'LBL_TRANSACTION_DETAIL',
          ),
          1 => 
          array (
            'name' => 'transaction_note',
            'studio' => 'visible',
            'label' => 'LBL_TRANSACTION_NOTE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'transaction_date',
            'label' => 'LBL_TRANSACTION_DATE',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
