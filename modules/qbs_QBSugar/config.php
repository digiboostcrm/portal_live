<?php
class Quickbooks_GokuConfig
{
	public $token;
	public $consumer_key;
	public $consumer_secret;
	public $url;
	public $response_url;
	public $dsn;
	public $encryption_key;
	public $username;
	public $tenant;
	public $IntuitAnywhere;
        public $quickbooks_is_connected;
        public $quickbooks_menu_url;
        public $quickbooks_CountryInfo;

	function __construct()
	{	
		global $db, $sugar_config;
		$module = 'qbs_QBSugar';
                $dbconfig = $sugar_config['dbconfig'];
                $site_URL = $sugar_config['site_url'];

		$token_err = 3;
		$query = $db->query("select * from sugar_qbtigersettings");
		$count = $db->getRowCount($query);
		if($count != 0)	{
			while($row = $db->fetchByAssoc($query))   {
                $this->token = $row['app_token'];
                $this->consumer_key = $row['consumer_key'];
                $this->consumer_secret = $row['consumer_secret'];
			}
        }

		// This is the URL of your OAuth auth handler page
        $this->url = $site_URL."/index.php?module={$module}&action=qbindex";

       // This is the URL to forward the user to after they have connected to IPP/IDS via OAuth
       $this->response_url = $site_URL."/index.php?module={$module}&action=qbresponse";

       $this->quickbooks_menu_url = $site_URL."/modules/{$module}/QBMenu.php";

       $this->dsn = $dbconfig['db_type']."://".$dbconfig['db_user_name'].":".$dbconfig['db_password']."@".$dbconfig['db_host_name']."/".$dbconfig['db_name'];

		# You should set this to an encryption key specific to your app
		$this->encryption_key = $sugar_config['unique_key'];

		# The user that's logged in
		$this->username = 'admin';

		# The tenant that user is accessing within your own app
		$this->tenant = md5($this->encryption_key);

		if(!QuickBooks_Utilities::initialized($this->dsn))
		{
        		// Initialize creates the neccessary table schema for queueing up requests and logging
	        	QuickBooks_Utilities::initialize($this->dsn);
		}

		$this->IntuitAnywhere = new QuickBooks_IPP_IntuitAnywhere($this->dsn, $this->encryption_key, $this->consumer_key, $this->consumer_secret, $this->url, $this->response_url);

        // Are they connected to QuickBooks right now? 
        if ($this->IntuitAnywhere->check($this->username, $this->tenant) and
                $this->IntuitAnywhere->test($this->username, $this->tenant))
        {
            // Yes, they are 
            $this->quickbooks_is_connected = true;

            // Set up the IPP instance
            $IPP = new QuickBooks_IPP($this->dsn);

            // Get our OAuth credentials from the database
            $creds = $this->IntuitAnywhere->load($this->username, $this->tenant);

            // Tell the framework to load some data from the OAuth store
            $IPP->authMode(
                    QuickBooks_IPP::AUTHMODE_OAUTH,
                    $this->username,
                    $creds);

            if ($sandbox)
            {
                // Turn on sandbox mode/URLs 
                $IPP->sandbox(true);
            }

            // Print the credentials we're using
            //print_r($creds);

            // This is our current realm
            $realm = $creds['qb_realm'];

	    if ($Context = $IPP->context())
	    {
		    $IPP->version(QuickBooks_IPP_IDS::VERSION_3);

		    // Set the DBID
		    $IPP->dbid($Context, 'dbid');

		    // Set the IPP flavor
		    $IPP->flavor($creds['qb_flavor']);

		    // Get the base URL if it's QBO
		    if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
		    {
//      	          $IPP->baseURL($IPP->getBaseURL($Context, $realm));
		    }

	    }

	    // Get some company info
	    $CompanyInfoService = new QuickBooks_IPP_Service_CompanyInfo();
	    $this->quickbooks_CountryInfo = $CompanyInfoService->get($Context, $realm);

        }
        else
        {
            // No, they are not
            $this->quickbooks_is_connected = false;
        }

	}	
}

