<?php
// created: 2019-05-23 13:55:09
$sugar_config = array (
  'SAML_X509Cert' => '',
  'SAML_loginurl' => '',
  'SAML_logouturl' => '',
  'addAjaxBannedModules' => 
  array (
    0 => 'Leads',
    1 => 'Contacts',
    2 => 'rls_Reports',
    3 => 'RLS_Scheduling_Reports',
    4 => 'rls_Dashboards',
    5 => 'SecurityGroups',
    6 => 'qbs_QBSugar',
    7 => 'qbs_QBSugar',
  ),
  'admin_access_control' => false,
  'admin_export_only' => false,
  'aod' => 
  array (
    'enable_aod' => true,
  ),
  'aop' => 
  array (
    'distribution_method' => 'roundRobin',
    'case_closure_email_template_id' => '72e0d95c-c97a-77f1-4c55-59b95146ee35',
    'joomla_account_creation_email_template_id' => '80f5f4df-9e1b-7c37-90fd-59b951a6b87c',
    'case_creation_email_template_id' => '8f60c53f-ef1d-1271-0392-59b951adec31',
    'contact_email_template_id' => '9dd47d99-580e-13fe-c732-59b9517ea4dd',
    'user_email_template_id' => 'adb7d70d-73fd-4ed9-326f-59b9519e7893',
    'enable_aop' => true,
    'joomla_url' => 'http://digiboost.com/portal_test',
    'enable_portal' => true,
    'distribution_user_id' => '',
    'distribution_options' => 
    array (
      0 => 'role',
      1 => '',
      2 => 'bf678315-6e11-5a0f-8ec3-59bbfcc2f5c0',
    ),
    'support_from_address' => 'do_not_reply@digiboost.com',
    'support_from_name' => 'Digiboost Support',
    'case_status_changes' => 'null',
  ),
  'aos' => 
  array (
    'version' => '5.3.3',
    'contracts' => 
    array (
      'renewalReminderPeriod' => '14',
    ),
    'lineItems' => 
    array (
      'totalTax' => false,
      'enableGroups' => true,
    ),
    'invoices' => 
    array (
      'initialNumber' => '1',
    ),
    'quotes' => 
    array (
      'initialNumber' => '1',
    ),
  ),
  'authenticationClass' => '',
  'cache_dir' => 'cache/',
  'calculate_response_time' => true,
  'calendar' => 
  array (
    'default_view' => 'week',
    'show_calls_by_default' => true,
    'show_tasks_by_default' => true,
    'show_completed_by_default' => true,
    'editview_width' => 990,
    'editview_height' => 485,
    'day_timestep' => 15,
    'week_timestep' => 30,
    'items_draggable' => true,
    'items_resizable' => true,
    'enable_repeat' => true,
    'max_repeat_count' => 1000,
  ),
  'chartEngine' => 'Jit',
  'common_ml_dir' => '',
  'create_default_user' => false,
  'cron' => 
  array (
    'max_cron_jobs' => 10,
    'max_cron_runtime' => 30,
    'min_cron_interval' => 30,
    'allowed_cron_users' => 
    array (
      0 => 'joshuahatfield',
      1 => 'apache',
    ),
  ),
  'currency' => '',
  'dashlet_auto_refresh_min' => '30',
  'dashlet_display_row_options' => 
  array (
    0 => '1',
    1 => '3',
    2 => '5',
    3 => '10',
  ),
  'date_formats' => 
  array (
    'Y-m-d' => '2010-12-23',
    'm-d-Y' => '12-23-2010',
    'd-m-Y' => '23-12-2010',
    'Y/m/d' => '2010/12/23',
    'm/d/Y' => '12/23/2010',
    'd/m/Y' => '23/12/2010',
    'Y.m.d' => '2010.12.23',
    'd.m.Y' => '23.12.2010',
    'm.d.Y' => '12.23.2010',
  ),
  'datef' => 'm/d/Y',
  'dbconfig' => 
  array (
    'db_host_name' => '172.24.16.132',
    'db_host_instance' => 'SQLEXPRESS',
    'db_user_name' => 'cm_portal_user',
    'db_password' => 'Ie5q5#v8',
    'db_name' => 'suitecrm_30',
    'db_type' => 'mysql',
    'db_port' => '',
    'db_manager' => 'MysqliManager',
  ),
  'dbconfigoption' => 
  array (
    'persistent' => true,
    'autofree' => false,
    'debug' => 0,
    'ssl' => false,
  ),
  'default_action' => 'index',
  'default_charset' => 'UTF-8',
  'default_currencies' => 
  array (
    'AUD' => 
    array (
      'name' => 'Australian Dollars',
      'iso4217' => 'AUD',
      'symbol' => '$',
    ),
    'BRL' => 
    array (
      'name' => 'Brazilian Reais',
      'iso4217' => 'BRL',
      'symbol' => 'R$',
    ),
    'GBP' => 
    array (
      'name' => 'British Pounds',
      'iso4217' => 'GBP',
      'symbol' => '£',
    ),
    'CAD' => 
    array (
      'name' => 'Canadian Dollars',
      'iso4217' => 'CAD',
      'symbol' => '$',
    ),
    'CNY' => 
    array (
      'name' => 'Chinese Yuan',
      'iso4217' => 'CNY',
      'symbol' => '￥',
    ),
    'EUR' => 
    array (
      'name' => 'Euro',
      'iso4217' => 'EUR',
      'symbol' => '€',
    ),
    'HKD' => 
    array (
      'name' => 'Hong Kong Dollars',
      'iso4217' => 'HKD',
      'symbol' => '$',
    ),
    'INR' => 
    array (
      'name' => 'Indian Rupees',
      'iso4217' => 'INR',
      'symbol' => '₨',
    ),
    'KRW' => 
    array (
      'name' => 'Korean Won',
      'iso4217' => 'KRW',
      'symbol' => '₩',
    ),
    'YEN' => 
    array (
      'name' => 'Japanese Yen',
      'iso4217' => 'JPY',
      'symbol' => '¥',
    ),
    'MXN' => 
    array (
      'name' => 'Mexican Pesos',
      'iso4217' => 'MXN',
      'symbol' => '$',
    ),
    'SGD' => 
    array (
      'name' => 'Singaporean Dollars',
      'iso4217' => 'SGD',
      'symbol' => '$',
    ),
    'CHF' => 
    array (
      'name' => 'Swiss Franc',
      'iso4217' => 'CHF',
      'symbol' => 'SFr.',
    ),
    'THB' => 
    array (
      'name' => 'Thai Baht',
      'iso4217' => 'THB',
      'symbol' => '฿',
    ),
    'USD' => 
    array (
      'name' => 'US Dollars',
      'iso4217' => 'USD',
      'symbol' => '$',
    ),
  ),
  'default_currency_iso4217' => 'USD',
  'default_currency_name' => 'US Dollars',
  'default_currency_significant_digits' => 2,
  'default_currency_symbol' => '$',
  'default_date_format' => 'm/d/Y',
  'default_decimal_seperator' => '.',
  'default_email_charset' => 'UTF-8',
  'default_email_client' => 'sugar',
  'default_email_editor' => 'html',
  'default_export_charset' => 'UTF-8',
  'default_language' => 'en_us',
  'default_locale_name_format' => 's f l',
  'default_max_tabs' => 10,
  'default_module' => 'Home',
  'default_module_favicon' => false,
  'default_navigation_paradigm' => 'gm',
  'default_number_grouping_seperator' => ',',
  'default_password' => '',
  'default_permissions' => 
  array (
    'dir_mode' => 1528,
    'file_mode' => 493,
    'user' => '',
    'group' => '',
  ),
  'default_subpanel_links' => false,
  'default_subpanel_tabs' => true,
  'default_swap_last_viewed' => false,
  'default_swap_shortcuts' => false,
  'default_theme' => 'SuiteP',
  'default_time_format' => 'h:ia',
  'default_user_is_admin' => false,
  'default_user_name' => '',
  'demoData' => 'yes',
  'developerMode' => true,
  'disable_convert_lead' => true,
  'disable_export' => false,
  'disable_persistent_connections' => false,
  'display_email_template_variable_chooser' => false,
  'display_inbound_email_buttons' => false,
  'dump_slow_queries' => true,
  'email_address_separator' => ',',
  'email_confirm_opt_in_email_template_id' => '',
  'email_default_client' => 'sugar',
  'email_default_delete_attachments' => false,
  'email_default_editor' => 'html',
  'email_enable_auto_send_opt_in' => true,
  'email_enable_confirm_opt_in' => 'not-opt-in',
  'email_xss' => 'YToxOntzOjY6InNjcmlwdCI7czo2OiJzY3JpcHQiO30=',
  'enable_action_menu' => true,
  'enable_line_editing_detail' => false,
  'enable_line_editing_list' => false,
  'export_delimiter' => ',',
  'export_excel_compatible' => false,
  'hide_subpanels' => true,
  'history_max_viewed' => 50,
  'host_name' => 'www.digiboost.com',
  'http_referer' => 
  array (
    'list' => 
    array (
      0 => 'rightsignature.com',
      1 => 'secure.sharefile.com',
      2 => 'appcenter.intuit.com',
    ),
  ),
  'import_max_execution_time' => 3600,
  'import_max_records_per_file' => 100,
  'import_max_records_total_limit' => '',
  'inbound_email_case_subject_macro' => '[Ticket : %1]',
  'installer_locked' => true,
  'jobs' => 
  array (
    'min_retry_interval' => 30,
    'max_retries' => 5,
    'timeout' => 86400,
  ),
  'js_custom_version' => 1,
  'js_lang_version' => 18,
  'languages' => 
  array (
    'en_us' => 'English (US)',
  ),
  'large_scale_test' => false,
  'lead_conv_activity_opt' => 'move',
  'list_max_entries_per_page' => 20,
  'list_max_entries_per_subpanel' => 10,
  'lock_default_user_name' => false,
  'lock_homepage' => false,
  'lock_subpanels' => false,
  'log_dir' => '.',
  'log_file' => 'suitecrm.log',
  'log_memory_usage' => true,
  'logger' => 
  array (
    'level' => 'fatal',
    'file' => 
    array (
      'ext' => '.log',
      'name' => 'suitecrm',
      'dateFormat' => '%c',
      'maxSize' => '10MB',
      'maxLogs' => 200,
      'suffix' => '%m_%d_%y',
    ),
  ),
  'max_dashlets_homepage' => '15',
  'name_formats' => 
  array (
    's f l' => 's f l',
    'f l' => 'f l',
    's l' => 's l',
    'l, s f' => 'l, s f',
    'l, f' => 'l, f',
    's l, f' => 's l, f',
    'l s f' => 'l s f',
    'l f s' => 'l f s',
  ),
  'oauth2' => 
  array (
    'refresh_token_lifetime' => 3600,
  ),
  'outfitters_licenses' => 
  array (
    'wordpress-customer-portal-pro' => 'f00ccc2ea5f021815cdc950b04389e35',
    'reports-for-sugarcrm-ce' => 'a83d7c3b4670e2cfa36d3aea82d4e639',
    'esignrightsignature' => '9d105e7299a03501315d31fbe01e854a',
    'webtomodule' => 'd20837e3e3249a8b7b457314236645e3',
    'multiupload-files-with-workflow' => '71b30802de631ebc0b8a5c0ec5b44cae',
    'quickbooks-suitecrm-integration' => '1f52ad3597b03aff52d64649c440ff69',
  ),
  'passwordsetting' => 
  array (
    'SystemGeneratedPasswordON' => '1',
    'generatepasswordtmpl' => '1b94d1e9-347d-a6c5-fd41-59b95174ed66',
    'lostpasswordtmpl' => '2a0c0e28-8d2f-434c-f1b5-59b951787edf',
    'forgotpasswordON' => true,
    'linkexpiration' => true,
    'linkexpirationtime' => 24,
    'linkexpirationtype' => 60,
    'systexpiration' => '0',
    'systexpirationtime' => 7,
    'systexpirationtype' => 1,
    'systexpirationlogin' => '',
    'minpwdlength' => 6,
    'oneupper' => false,
    'onelower' => false,
    'onenumber' => false,
    'factoremailtmpl' => '',
    'onespecial' => '0',
  ),
  'portal_view' => 'single_user',
  'require_accounts' => false,
  'resource_management' => 
  array (
    'special_query_limit' => 50000,
    'special_query_modules' => 
    array (
      0 => 'Reports',
      1 => 'Export',
      2 => 'Import',
      3 => 'Administration',
      4 => 'Sync',
      5 => 'Contacts',
    ),
    'default_limit' => 20000,
  ),
  'rs_template_individual_joint' => 'a9264422-0122-422b-8a59-13bbf676d95d',
  'rss_cache_time' => '10800',
  'save_query' => 'all',
  'search_wildcard_char' => '%',
  'search_wildcard_infront' => false,
  'securitysuite_additive' => true,
  'securitysuite_filter_user_list' => false,
  'securitysuite_inbound_email' => false,
  'securitysuite_inherit_assigned' => true,
  'securitysuite_inherit_creator' => true,
  'securitysuite_inherit_parent' => true,
  'securitysuite_popup_select' => false,
  'securitysuite_strict_rights' => false,
  'securitysuite_user_popup' => true,
  'securitysuite_user_role_precedence' => true,
  'securitysuite_version' => '6.5.17',
  'session_dir' => '',
  'showDetailData' => true,
  'showThemePicker' => true,
  'site_url' => 'https://digiboost.com/portal',
  'slow_query_time_msec' => '10000',
  'stack_trace_errors' => false,
  'sugar_version' => '6.5.25',
  'sugarbeet' => false,
  'suitecrm_version' => '7.10.7',
  'system_email_templates' => 
  array (
    'confirm_opt_in_template_id' => '84df21e1-e8fb-84d4-5565-5b86f9fe425c',
  ),
  'time_formats' => 
  array (
    'H:i' => '23:00',
    'h:ia' => '11:00pm',
    'h:iA' => '11:00PM',
    'h:i a' => '11:00 pm',
    'h:i A' => '11:00 PM',
    'H.i' => '23.00',
    'h.ia' => '11.00pm',
    'h.iA' => '11.00PM',
    'h.i a' => '11.00 pm',
    'h.i A' => '11.00 PM',
  ),
  'timef' => 'H:i',
  'tmp_dir' => 'cache/xml/',
  'tracker_max_display_length' => 15,
  'translation_string_prefix' => false,
  'unique_key' => 'cm-cust-portal-session',
  'upload_badext' => 
  array (
    0 => 'php',
    1 => 'php3',
    2 => 'php4',
    3 => 'php5',
    4 => 'pl',
    5 => 'cgi',
    6 => 'py',
    7 => 'asp',
    8 => 'cfm',
    9 => 'js',
    10 => 'vbs',
    11 => 'html',
    12 => 'htm',
    13 => 'phtml',
  ),
  'upload_dir' => 'upload/',
  'upload_maxsize' => 30000000,
  'use_common_ml_dir' => false,
  'use_real_names' => true,
  'vcal_time' => '2',
  'verify_client_ip' => false,
);