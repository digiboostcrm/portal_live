<?php

$outfitters_config = array(
    'shortname' => 'reports-for-sugarcrm-ce', //The short name of the Add-on. e.g. For the url https://www.sugaroutfitters.com/addons/sugaroutfitters the shortname would be sugaroutfitters
    'public_key' => 'c39e303dc5d377bdf2fccc44bdb86563,19b388e134cdca7a6d731787193365da', //The public key associated with the group
    'suite_api_url' => 'https://store.suitecrm.com/api/v1',
    'api_url' => 'https://www.sugaroutfitters.com/api/v1',
    'api_url_rls' => 'http://licensing.crm-recruitment.com//api/v1',
    'validate_users' => true,
    'manage_licensed_users' => true,
    'validation_frequency' => 'weekly', //default: weekly options: hourly, daily, weekly
    'continue_url' => '', //[optional] Will show a button after license validation that will redirect to this page. Could be used to redirect to a configuration page such as index.php?module=MyCustomModule&action=config
);
