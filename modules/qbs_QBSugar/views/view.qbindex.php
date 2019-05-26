<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/MVC/View/views/view.list.php');
class qbs_QBSugarViewQBIndex extends ViewList
{
	public function display()       {
		global $db, $sugar_config;
		$module = $this->module;
		$dbconfig = $sugar_config['dbconfig'];
		$site_URL = $sugar_config['site_url'];
		require_once("modules/{$module}/QuickBooks.php");
        $query = $db->query("select * from sugar_qbtigersettings");
        $count = $db->getRowCount($query);
        if($count != 0) {
            while($row = $db->fetchByAssoc($query))   {
                $token = $row['app_token'];
                $oauth_consumer_key = $row['consumer_key'];
                $oauth_consumer_secret = $row['consumer_secret'];

            }
        }

		// This is the URL of your OAuth auth handler page
		$this_url = $site_URL."/index.php?module={$this->module}&action=qbindex";

		// This is the URL to forward the user to after they have connected to IPP/IDS via OAuth
		$that_url = $site_URL."/index.php?module={$module}&action=qbresponse";
		$dsn = $dbconfig['db_type']."://".$dbconfig['db_user_name'].":".$dbconfig['db_password']."@".$dbconfig['db_host_name']."/".$dbconfig['db_name'];

		$encryption_key = $sugar_config['unique_key'];
		$the_username = 'admin';
		$the_tenant = md5($encryption_key);

		if(!QuickBooks_Utilities::initialized($dsn))	{
			// Initialize creates the neccessary database schema for queueing up requests and logging
			QuickBooks_Utilities::initialize($dsn);
		}

		$IntuitAnywhere = new QuickBooks_IPP_IntuitAnywhere($dsn, $encryption_key, $oauth_consumer_key, $oauth_consumer_secret, $this_url, $that_url);

		if($IntuitAnywhere->handle($the_username, $the_tenant))	{
			// The user has been connected, and will be redirected to $that_url automatically. 
		}
		else	{
			die('Oh no, something bad happened: ' . $IntuitAnywhere->errorNumber() . ': ' . $IntuitAnywhere->errorMessage());
		}

	}
}

