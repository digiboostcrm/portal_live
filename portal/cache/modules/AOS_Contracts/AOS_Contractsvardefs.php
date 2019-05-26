<?php 
 $GLOBALS["dictionary"]["AOS_Contracts"]=array (
  'table' => 'aos_contracts',
  'audited' => true,
  'fields' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'vname' => 'LBL_ID',
      'type' => 'id',
      'required' => true,
      'reportable' => true,
      'comment' => 'Unique identifier',
      'inline_edit' => false,
    ),
    'name' => 
    array (
      'name' => 'name',
      'vname' => 'LBL_NAME',
      'type' => 'name',
      'dbType' => 'varchar',
      'len' => '255',
      'unified_search' => true,
      'required' => true,
      'importable' => 'required',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'size' => '20',
    ),
    'date_entered' => 
    array (
      'name' => 'date_entered',
      'vname' => 'LBL_DATE_ENTERED',
      'type' => 'datetime',
      'group' => 'created_by_name',
      'comment' => 'Date record created',
      'enable_range_search' => true,
      'options' => 'date_range_search_dom',
      'inline_edit' => false,
    ),
    'date_modified' => 
    array (
      'name' => 'date_modified',
      'vname' => 'LBL_DATE_MODIFIED',
      'type' => 'datetime',
      'group' => 'modified_by_name',
      'comment' => 'Date record last modified',
      'enable_range_search' => true,
      'options' => 'date_range_search_dom',
      'inline_edit' => false,
    ),
    'modified_user_id' => 
    array (
      'name' => 'modified_user_id',
      'rname' => 'user_name',
      'id_name' => 'modified_user_id',
      'vname' => 'LBL_MODIFIED',
      'type' => 'assigned_user_name',
      'table' => 'users',
      'isnull' => 'false',
      'group' => 'modified_by_name',
      'dbType' => 'id',
      'reportable' => true,
      'comment' => 'User who last modified record',
      'massupdate' => false,
      'inline_edit' => false,
    ),
    'modified_by_name' => 
    array (
      'name' => 'modified_by_name',
      'vname' => 'LBL_MODIFIED_NAME',
      'type' => 'relate',
      'reportable' => false,
      'source' => 'non-db',
      'rname' => 'user_name',
      'table' => 'users',
      'id_name' => 'modified_user_id',
      'module' => 'Users',
      'link' => 'modified_user_link',
      'duplicate_merge' => 'disabled',
      'massupdate' => false,
      'inline_edit' => false,
    ),
    'created_by' => 
    array (
      'name' => 'created_by',
      'rname' => 'user_name',
      'id_name' => 'modified_user_id',
      'vname' => 'LBL_CREATED',
      'type' => 'assigned_user_name',
      'table' => 'users',
      'isnull' => 'false',
      'dbType' => 'id',
      'group' => 'created_by_name',
      'comment' => 'User who created record',
      'massupdate' => false,
      'inline_edit' => false,
    ),
    'created_by_name' => 
    array (
      'name' => 'created_by_name',
      'vname' => 'LBL_CREATED',
      'type' => 'relate',
      'reportable' => false,
      'link' => 'created_by_link',
      'rname' => 'user_name',
      'source' => 'non-db',
      'table' => 'users',
      'id_name' => 'created_by',
      'module' => 'Users',
      'duplicate_merge' => 'disabled',
      'importable' => 'false',
      'massupdate' => false,
      'inline_edit' => false,
    ),
    'description' => 
    array (
      'name' => 'description',
      'vname' => 'LBL_DESCRIPTION',
      'type' => 'text',
      'comment' => 'Full text of the note',
      'rows' => 6,
      'cols' => 80,
    ),
    'deleted' => 
    array (
      'name' => 'deleted',
      'vname' => 'LBL_DELETED',
      'type' => 'bool',
      'default' => '0',
      'reportable' => false,
      'comment' => 'Record deletion indicator',
    ),
    'created_by_link' => 
    array (
      'name' => 'created_by_link',
      'type' => 'link',
      'relationship' => 'aos_contracts_created_by',
      'vname' => 'LBL_CREATED_USER',
      'link_type' => 'one',
      'module' => 'Users',
      'bean_name' => 'User',
      'source' => 'non-db',
    ),
    'modified_user_link' => 
    array (
      'name' => 'modified_user_link',
      'type' => 'link',
      'relationship' => 'aos_contracts_modified_user',
      'vname' => 'LBL_MODIFIED_USER',
      'link_type' => 'one',
      'module' => 'Users',
      'bean_name' => 'User',
      'source' => 'non-db',
    ),
    'assigned_user_id' => 
    array (
      'name' => 'assigned_user_id',
      'rname' => 'user_name',
      'id_name' => 'assigned_user_id',
      'vname' => 'LBL_ASSIGNED_TO_ID',
      'group' => 'assigned_user_name',
      'type' => 'relate',
      'table' => 'users',
      'module' => 'Users',
      'reportable' => true,
      'isnull' => 'false',
      'dbType' => 'id',
      'audited' => true,
      'comment' => 'User ID assigned to record',
      'duplicate_merge' => 'disabled',
    ),
    'assigned_user_name' => 
    array (
      'name' => 'assigned_user_name',
      'link' => 'assigned_user_link',
      'vname' => 'LBL_ASSIGNED_TO_NAME',
      'rname' => 'user_name',
      'type' => 'relate',
      'reportable' => false,
      'source' => 'non-db',
      'table' => 'users',
      'id_name' => 'assigned_user_id',
      'module' => 'Users',
      'duplicate_merge' => 'disabled',
    ),
    'assigned_user_link' => 
    array (
      'name' => 'assigned_user_link',
      'type' => 'link',
      'relationship' => 'aos_contracts_assigned_user',
      'vname' => 'LBL_ASSIGNED_TO_USER',
      'link_type' => 'one',
      'module' => 'Users',
      'bean_name' => 'User',
      'source' => 'non-db',
      'duplicate_merge' => 'enabled',
      'rname' => 'user_name',
      'id_name' => 'assigned_user_id',
      'table' => 'users',
    ),
    'SecurityGroups' => 
    array (
      'name' => 'SecurityGroups',
      'type' => 'link',
      'relationship' => 'securitygroups_aos_contracts',
      'module' => 'SecurityGroups',
      'bean_name' => 'SecurityGroup',
      'source' => 'non-db',
      'vname' => 'LBL_SECURITYGROUPS',
    ),
    'reference_code' => 
    array (
      'required' => false,
      'name' => 'reference_code',
      'vname' => 'LBL_REFERENCE_CODE ',
      'type' => 'varchar',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'len' => '255',
      'size' => '20',
    ),
    'start_date' => 
    array (
      'required' => false,
      'name' => 'start_date',
      'vname' => 'LBL_START_DATE',
      'type' => 'date',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'size' => '20',
      'enable_range_search' => true,
      'options' => 'date_range_search_dom',
      'display_default' => 'now',
    ),
    'end_date' => 
    array (
      'required' => false,
      'name' => 'end_date',
      'vname' => 'LBL_END_DATE',
      'type' => 'date',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'size' => '20',
      'enable_range_search' => true,
      'options' => 'date_range_search_dom',
      'display_default' => '+1 year',
    ),
    'total_contract_value' => 
    array (
      'required' => false,
      'name' => 'total_contract_value',
      'vname' => 'LBL_TOTAL_CONTRACT_VALUE',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'len' => '26,6',
      'size' => '10',
      'enable_range_search' => true,
      'options' => 'numeric_range_search_dom',
    ),
    'total_contract_value_usdollar' => 
    array (
      'name' => 'total_contract_value_usdollar',
      'vname' => 'LBL_TOTAL_CONTRACT_VALUE_USDOLLAR',
      'type' => 'currency',
      'group' => 'amount',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'currency_id' => 
    array (
      'required' => false,
      'name' => 'currency_id',
      'vname' => 'LBL_CURRENCY',
      'type' => 'id',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => false,
      'reportable' => true,
      'len' => 36,
      'size' => '20',
      'studio' => 'visible',
      'function' => 
      array (
        'name' => 'getCurrencyDropDown',
        'returns' => 'html',
      ),
    ),
    'status' => 
    array (
      'required' => true,
      'name' => 'status',
      'vname' => 'LBL_STATUS',
      'type' => 'enum',
      'massupdate' => 0,
      'default' => 'Not Started',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'len' => 100,
      'size' => '20',
      'options' => 'contract_status_list',
      'studio' => 'visible',
      'dependency' => false,
    ),
    'customer_signed_date' => 
    array (
      'required' => false,
      'name' => 'customer_signed_date',
      'vname' => 'LBL_CUSTOMER_SIGNED_DATE',
      'type' => 'date',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'size' => '20',
      'enable_range_search' => false,
    ),
    'company_signed_date' => 
    array (
      'required' => false,
      'name' => 'company_signed_date',
      'vname' => 'LBL_COMPANY_SIGNED_DATE',
      'type' => 'date',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'size' => '20',
      'enable_range_search' => false,
    ),
    'renewal_reminder_date' => 
    array (
      'required' => false,
      'name' => 'renewal_reminder_date',
      'vname' => 'LBL_RENEWAL_REMINDER_DATE',
      'dbType' => 'datetime',
      'type' => 'datetimecombo',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'size' => '20',
      'enable_range_search' => true,
      'options' => 'date_range_search_dom',
    ),
    'contract_type' => 
    array (
      'required' => false,
      'name' => 'contract_type',
      'vname' => 'LBL_CONTRACT_TYPE',
      'type' => 'enum',
      'massupdate' => 0,
      'default' => 'Type',
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'len' => 100,
      'size' => '20',
      'options' => 'contract_type_list',
      'studio' => 'visible',
      'dependency' => false,
    ),
    'contract_account_id' => 
    array (
      'required' => false,
      'name' => 'contract_account_id',
      'vname' => '',
      'type' => 'id',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => 0,
      'reportable' => 0,
      'len' => 36,
    ),
    'contract_account' => 
    array (
      'required' => true,
      'source' => 'non-db',
      'name' => 'contract_account',
      'vname' => 'LBL_CONTRACT_ACCOUNT',
      'type' => 'relate',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => true,
      'relationship' => 'account_aos_contracts',
      'len' => '255',
      'id_name' => 'contract_account_id',
      'ext2' => 'Accounts',
      'module' => 'Accounts',
      'quicksearch' => 'enabled',
      'studio' => 'visible',
    ),
    'opportunity_id' => 
    array (
      'required' => false,
      'name' => 'opportunity_id',
      'vname' => '',
      'type' => 'id',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => 0,
      'reportable' => 0,
      'len' => 36,
    ),
    'opportunity' => 
    array (
      'required' => false,
      'source' => 'non-db',
      'name' => 'opportunity',
      'vname' => 'LBL_OPPORTUNITY',
      'type' => 'relate',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 1,
      'reportable' => true,
      'len' => '255',
      'id_name' => 'opportunity_id',
      'ext2' => 'Opportunities',
      'module' => 'Opportunities',
      'quicksearch' => 'enabled',
      'studio' => 'visible',
    ),
    'contact_id' => 
    array (
      'required' => false,
      'name' => 'contact_id',
      'vname' => '',
      'type' => 'id',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => 0,
      'reportable' => 0,
      'len' => 36,
    ),
    'contact' => 
    array (
      'required' => false,
      'source' => 'non-db',
      'name' => 'contact',
      'vname' => 'LBL_CONTACT',
      'type' => 'relate',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 1,
      'reportable' => true,
      'len' => '255',
      'id_name' => 'contact_id',
      'ext2' => 'Contacts',
      'module' => 'Contacts',
      'quicksearch' => 'enabled',
      'studio' => 'visible',
    ),
    'call_id' => 
    array (
      'required' => false,
      'name' => 'call_id',
      'vname' => 'LBL_CALL_ID',
      'type' => 'id',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => 0,
      'reportable' => 0,
      'len' => 36,
    ),
    'line_items' => 
    array (
      'required' => false,
      'name' => 'line_items',
      'vname' => 'LBL_LINE_ITEMS',
      'type' => 'function',
      'source' => 'non-db',
      'massupdate' => 0,
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => false,
      'reportable' => false,
      'function' => 
      array (
        'name' => 'display_lines',
        'returns' => 'html',
        'include' => 'modules/AOS_Products_Quotes/Line_Items.php',
      ),
    ),
    'total_amt' => 
    array (
      'required' => false,
      'name' => 'total_amt',
      'vname' => 'LBL_TOTAL_AMT',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 1,
      'reportable' => true,
      'len' => '26,6',
    ),
    'total_amt_usdollar' => 
    array (
      'name' => 'total_amt_usdollar',
      'vname' => 'LBL_TOTAL_AMT_USDOLLAR',
      'type' => 'currency',
      'group' => 'total_amt',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'subtotal_amount' => 
    array (
      'required' => false,
      'name' => 'subtotal_amount',
      'vname' => 'LBL_SUBTOTAL_AMOUNT',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 1,
      'reportable' => true,
      'len' => '26,6',
    ),
    'subtotal_amount_usdollar' => 
    array (
      'name' => 'subtotal_amount_usdollar',
      'vname' => 'LBL_SUBTOTAL_AMOUNT_USDOLLAR',
      'type' => 'currency',
      'group' => 'subtotal_amount',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'discount_amount' => 
    array (
      'required' => false,
      'name' => 'discount_amount',
      'vname' => 'LBL_DISCOUNT_AMOUNT',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 1,
      'reportable' => true,
      'len' => '26,6',
    ),
    'discount_amount_usdollar' => 
    array (
      'name' => 'discount_amount_usdollar',
      'vname' => 'LBL_DISCOUNT_AMOUNT_USDOLLAR',
      'type' => 'currency',
      'group' => 'discount_amount',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'tax_amount' => 
    array (
      'required' => false,
      'name' => 'tax_amount',
      'vname' => 'LBL_TAX_AMOUNT',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 1,
      'reportable' => true,
      'len' => '26,6',
    ),
    'tax_amount_usdollar' => 
    array (
      'name' => 'tax_amount_usdollar',
      'vname' => 'LBL_TAX_AMOUNT_USDOLLAR',
      'type' => 'currency',
      'group' => 'tax_amount',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'shipping_amount' => 
    array (
      'required' => false,
      'name' => 'shipping_amount',
      'vname' => 'LBL_SHIPPING_AMOUNT',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => true,
      'len' => '26,6',
    ),
    'shipping_amount_usdollar' => 
    array (
      'name' => 'shipping_amount_usdollar',
      'vname' => 'LBL_SHIPPING_AMOUNT_USDOLLAR',
      'type' => 'currency',
      'group' => 'shipping_amount',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'shipping_tax' => 
    array (
      'required' => false,
      'name' => 'shipping_tax',
      'vname' => 'LBL_SHIPPING_TAX',
      'type' => 'enum',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => true,
      'len' => 100,
      'options' => 'vat_list',
      'studio' => 'visible',
    ),
    'shipping_tax_amt' => 
    array (
      'required' => false,
      'name' => 'shipping_tax_amt',
      'vname' => 'LBL_SHIPPING_TAX_AMT',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => 0,
      'reportable' => true,
      'len' => '26,6',
      'size' => '10',
      'enable_range_search' => false,
      'function' => 
      array (
        'name' => 'display_shipping_vat',
        'returns' => 'html',
        'include' => 'modules/AOS_Products_Quotes/Line_Items.php',
      ),
    ),
    'shipping_tax_amt_usdollar' => 
    array (
      'name' => 'shipping_tax_amt_usdollar',
      'vname' => 'LBL_SHIPPING_TAX_AMT_USDOLLAR',
      'type' => 'currency',
      'group' => 'shipping_tax_amt',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'total_amount' => 
    array (
      'required' => false,
      'name' => 'total_amount',
      'vname' => 'LBL_GRAND_TOTAL',
      'type' => 'currency',
      'massupdate' => 0,
      'comments' => '',
      'help' => '',
      'importable' => 'true',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => '0',
      'audited' => false,
      'reportable' => true,
      'len' => '26,6',
      'enable_range_search' => true,
      'options' => 'numeric_range_search_dom',
    ),
    'total_amount_usdollar' => 
    array (
      'name' => 'total_amount_usdollar',
      'vname' => 'LBL_GRAND_TOTAL_USDOLLAR',
      'type' => 'currency',
      'group' => 'total_amount',
      'disable_num_format' => true,
      'duplicate_merge' => '0',
      'audited' => true,
      'comment' => '',
      'studio' => 
      array (
        'editview' => false,
        'detailview' => false,
        'quickcreate' => false,
      ),
      'len' => '26,6',
    ),
    'accounts' => 
    array (
      'name' => 'accounts',
      'vname' => 'LBL_ACCOUNTS',
      'type' => 'link',
      'relationship' => 'account_aos_contracts',
      'module' => 'Accounts',
      'bean_name' => 'Account',
      'source' => 'non-db',
    ),
    'contacts' => 
    array (
      'name' => 'contacts',
      'vname' => 'LBL_CONTACTS',
      'type' => 'link',
      'relationship' => 'contact_aos_contracts',
      'module' => 'Contacts',
      'bean_name' => 'Contact',
      'source' => 'non-db',
    ),
    'tasks' => 
    array (
      'name' => 'tasks',
      'vname' => 'LBL_TASKS',
      'type' => 'link',
      'relationship' => 'aos_contracts_tasks',
      'module' => 'Tasks',
      'bean_name' => 'Task',
      'source' => 'non-db',
    ),
    'notes' => 
    array (
      'name' => 'notes',
      'vname' => 'LBL_NOTES',
      'type' => 'link',
      'relationship' => 'aos_contracts_notes',
      'module' => 'Notes',
      'bean_name' => 'Note',
      'source' => 'non-db',
    ),
    'meetings' => 
    array (
      'name' => 'meetings',
      'vname' => 'LBL_MEETINGS',
      'type' => 'link',
      'relationship' => 'aos_contracts_meetings',
      'module' => 'Meetings',
      'bean_name' => 'Meeting',
      'source' => 'non-db',
    ),
    'calls' => 
    array (
      'name' => 'calls',
      'vname' => 'LBL_CALLS',
      'type' => 'link',
      'relationship' => 'aos_contracts_calls',
      'module' => 'Calls',
      'bean_name' => 'Call',
      'source' => 'non-db',
    ),
    'emails' => 
    array (
      'name' => 'emails',
      'vname' => 'LBL_EMAILS',
      'type' => 'link',
      'relationship' => 'emails_aos_contracts_rel',
      'source' => 'non-db',
    ),
    'aos_quotes_aos_contracts' => 
    array (
      'name' => 'aos_quotes_aos_contracts',
      'vname' => 'LBL_AOS_QUOTES_AOS_CONTRACTS',
      'type' => 'link',
      'relationship' => 'aos_quotes_aos_contracts',
      'source' => 'non-db',
      'module' => 'AOS_Quotes',
    ),
    'documents' => 
    array (
      'name' => 'documents',
      'vname' => 'LBL_DOCUMENTS',
      'type' => 'link',
      'relationship' => 'aos_contracts_documents',
      'source' => 'non-db',
      'module' => 'Documents',
    ),
    'aos_products_quotes' => 
    array (
      'name' => 'aos_products_quotes',
      'vname' => 'LBL_AOS_PRODUCT_QUOTES',
      'type' => 'link',
      'relationship' => 'aos_contracts_aos_products_quotes',
      'module' => 'AOS_Products_Quotes',
      'bean_name' => 'AOS_Products_Quotes',
      'source' => 'non-db',
    ),
    'aos_line_item_groups' => 
    array (
      'name' => 'aos_line_item_groups',
      'vname' => 'LBL_AOS_LINE_ITEM_GROUPS',
      'type' => 'link',
      'relationship' => 'aos_contracts_aos_line_item_groups',
      'module' => 'AOS_Line_Item_Groups',
      'bean_name' => 'AOS_Line_Item_Groups',
      'source' => 'non-db',
    ),
    'name_created_by' => 
    array (
      'name' => 'name_created_by',
      'studio' => true,
      'required' => false,
      'vname' => 'LBL_CREATED_BY',
      'type' => 'varchar',
      'default_value' => '',
      'importable' => true,
      'audited' => false,
      'reportable' => true,
    ),
    'created_from_c' => 
    array (
      'inline_edit' => 1,
      'duplicate_merge_dom_value' => 0,
      'required' => true,
      'source' => 'custom_fields',
      'name' => 'created_from_c',
      'vname' => 'LBL_CREATED_FROM',
      'type' => 'enum',
      'massupdate' => '0',
      'default' => '0',
      'no_default' => false,
      'comments' => '',
      'help' => '',
      'importable' => 'required',
      'duplicate_merge' => 'disabled',
      'audited' => false,
      'reportable' => true,
      'unified_search' => false,
      'merge_filter' => 'disabled',
      'len' => 100,
      'size' => '20',
      'options' => 'created_from_options',
      'studio' => 'visible',
      'dependency' => false,
      'id' => 'AOS_Contractscreated_from_c',
      'custom_module' => 'AOS_Contracts',
    ),
    'custom_file' => 
    array (
      'name' => 'custom_file',
      'studio' => true,
      'required' => false,
      'vname' => 'LBL_CCUSTOM_FILE',
      'type' => 'file',
      'default_value' => '',
      'importable' => true,
      'audited' => false,
      'reportable' => true,
    ),
  ),
  'relationships' => 
  array (
    'aos_contracts_modified_user' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Contracts',
      'rhs_table' => 'aos_contracts',
      'rhs_key' => 'modified_user_id',
      'relationship_type' => 'one-to-many',
    ),
    'aos_contracts_created_by' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Contracts',
      'rhs_table' => 'aos_contracts',
      'rhs_key' => 'created_by',
      'relationship_type' => 'one-to-many',
    ),
    'aos_contracts_assigned_user' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Contracts',
      'rhs_table' => 'aos_contracts',
      'rhs_key' => 'assigned_user_id',
      'relationship_type' => 'one-to-many',
    ),
    'securitygroups_aos_contracts' => 
    array (
      'lhs_module' => 'SecurityGroups',
      'lhs_table' => 'securitygroups',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Contracts',
      'rhs_table' => 'aos_contracts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'securitygroups_records',
      'join_key_lhs' => 'securitygroup_id',
      'join_key_rhs' => 'record_id',
      'relationship_role_column' => 'module',
      'relationship_role_column_value' => 'AOS_Contracts',
    ),
    'aos_contracts_tasks' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Tasks',
      'rhs_table' => 'tasks',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'AOS_Contracts',
    ),
    'aos_contracts_notes' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'AOS_Contracts',
    ),
    'aos_contracts_meetings' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Meetings',
      'rhs_table' => 'meetings',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'AOS_Contracts',
    ),
    'aos_contracts_calls' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Calls',
      'rhs_table' => 'calls',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
      'relationship_role_column_value' => 'AOS_Contracts',
    ),
    'aos_contracts_aos_products_quotes' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Products_Quotes',
      'rhs_table' => 'aos_products_quotes',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
    ),
    'aos_contracts_aos_line_item_groups' => 
    array (
      'lhs_module' => 'AOS_Contracts',
      'lhs_table' => 'aos_contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Line_Item_Groups',
      'rhs_table' => 'aos_line_item_groups',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
    ),
  ),
  'optimistic_locking' => true,
  'unified_search' => true,
  'indices' => 
  array (
    'id' => 
    array (
      'name' => 'aos_contractspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
  ),
  'templates' => 
  array (
    'security_groups' => 'security_groups',
    'assignable' => 'assignable',
    'basic' => 'basic',
  ),
  'custom_fields' => true,
);