<?php

$outfitters_config = array(
    'name' => 'eSignRightSignature', //The matches the id value in your manifest file. This allow the library to lookup addon version from upgrade_history, so you can see what version of addon your customers are using
    'shortname' => 'esignrightsignature', //The short name of the Add-on. e.g. For the url https://www.sugaroutfitters.com/addons/sugaroutfitters the shortname would be sugaroutfitters
    'public_key' => 'db86a8f6634fb8279055649edfb0430c', //The public key associated with the group
    'api_url' => 'https://store.suitecrm.com/api/v1',
    'validate_users' => false,
    'manage_licensed_users' => false, //Enable the user management tool to determine which users will be licensed to use the add-on. validate_users must be set to true if this is enabled. If the add-on must be licensed for all users then set this to false.
    'validation_frequency' => 'daily', //default: weekly options: hourly, daily, weekly
    'continue_url' => 'index.php?module=UT_RightSignature&action=RightSignatureAppKeys', //[optional] Will show a button after license validation that will redirect to this page. Could be used to redirect to a configuration page such as index.php?module=MyCustomModule&action=config
);