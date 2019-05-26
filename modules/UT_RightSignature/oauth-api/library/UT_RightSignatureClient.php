<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
require_once('modules/UT_RightSignature/oauth-api/http.php');
require_once('modules/UT_RightSignature/oauth-api/oauth_client.php');
require_once('modules/UT_RightSignature/oauth-api/database_oauth_client.php');
require_once('modules/UT_RightSignature/oauth-api/mysqli_oauth_client.php');
require_once('modules/UT_RightSignature/RightSignatureUtils.php');

global $db, $current_user;

class UT_RightSignatureClient {
   var $client = NULL;
   var $api_url = 'https://api.rightsignature.com/public/v1';
   function __construct($scope='',$isCronJob = false,$current_user_id='1')
   {
       global $sugar_config, $current_user;
       if (is_null($this->client)) 
            $this->client = new mysqli_oauth_client_class;
       
       $Server = 'RightSignature';
       $this->client->server = $Server;
       $aClientSecrets = $this->getSugarClientSecrets($Server);
       $this->client->client_id = $aClientSecrets['consumer_key']; $application_line = __LINE__;
       $this->client->client_secret = $aClientSecrets['consumer_secret'];
       $this->client->offline = true;
       $this->client->debug = true;
       $this->client->debug_http = true;
       if(empty($scope))
            $this->client->scope = 'read write';
       else
            $this->client->scope = $scope;

       //$this->client->redirect_uri = $sugar_config['site_url']."/index.php?entryPoint=RightSignatureCallback";
       $this->client->redirect_uri = $sugar_config['site_url']."/UT_RSCallback.php";
       if(strlen($this->client->client_id) == 0
        || strlen($this->client->client_secret) == 0)
        {
            if($isCronJob == true){
                $GLOBALS['log']->fatal('Please go to RightSignature new application page '.
                'https://secure.rightsignature.com/account/user/api , '.
                ' set the client_id to Consumer key and client_secret with Consumer secret. '.
                'The Callback URL must be '.$this->client->redirect_uri.' If you want to post to '.
                'the user timeline, make sure the application you create has write permissions');
            }
            else
            {
               die('Please go to RightSignature new application page '.
                'https://secure.rightsignature.com/account/user/api , '.
                ' set the client_id to Consumer key and client_secret with Consumer secret. '.
                'The Callback URL must be '.$this->client->redirect_uri.' If you want to post to '.
                'the user timeline, make sure the application you create has write permissions');
            }
        }
        if ($isCronJob == true && !empty($current_user_id)) 
        {
            $iUserID = $current_user_id;
        }
        else
        {
            $iUserID = $current_user->id;
        }
        $iUserID = 1;
        $aUToAuthDBSettings = getOAuthDBSettings($Server, $iUserID);
        
        if(!empty($aUToAuthDBSettings)){
            $this->client->user=$iUserID;
            $this->client->Initialize();
        }
        else{
            if($isCronJob == true){
                $GLOBALS['log']->fatal('Please go to RightSignature new application page '.
                                        'https://secure.rightsignature.com/account/user/api , '.
                                        ' set the client_id to Consumer key and client_secret with Consumer secret. '.
                                        'The Callback URL must be '.$this->client->redirect_uri.' If you want to post to '.
                                        'the user timeline, make sure the application you create has write permissions');
            }
            else
                $this->UT_Auth();
        }
   }
   public function UT_Auth()
   {
       global $current_user; 
       $iUserID = 1;
        if (($success = $this->client->Initialize())) {
            if (($success = $this->client->Process())) {
                if (strlen($this->client->authorization_error)) {
                    $this->client->error = $this->client->authorization_error;
                    $success = false;
                } else if (strlen($this->client->access_token)) {
                    $success = $this->client->CallAPI(
                        'https://api.rightsignature.com/public/v1/me', 
                        'GET', array(), array('FailOnAccessError'=>true), $user);

                    if($success)
                        $success = $this->client->SetUser($iUserID);
                        //$success = $this->client->SetUser($current_user->id);
                    //$this->client->Finalize($success);
                    //header('Location: ' . $this->client->redirect_uri);
                    //exit;
                }
            }
            $this->client->Finalize($success);
        }
    }
    function getSugarClientSecrets($Server)
    {
       global $db,$sugar_config;
       $aReturn = array();
       $sSql = "SELECT * FROM ut_rightsignature_api_keys WHERE deleted=0 ";
       $sSql = $db->limitQuery($sSql, 0, 1,false,'',false);
       $oRes = $db->query($sSql,true);
       $aRow = $db->fetchByAssoc($oRes);
       $aReturn['consumer_key'] = $aRow['consumer_key'];
       $aReturn['consumer_secret'] = $aRow['consumer_secret'];
       return $aReturn;
    }
    function __apiCall($url,$method='GET',$parameters=array(),$options = array())
    {
       if(empty($options))
       {
           $options = array('FailOnAccessError'=>true);
       }
       $success = $this->client->CallAPI($url, $method, $parameters, $options, $aResponse);
       $success = $this->client->Finalize($success);
       return $aResponse;
    }
    // 
    // Returns json response from RightSignature's list of Documents
    // 
    // Arguments:
    // $tags (optional) - associative array of tag names and values that are associated with documents. 
    //         ex. array('customized' => '', 'user id' => '123')
    // $query (optional) = Search term to narrow results. Should be URI encoded.
    //         ex. 'State Street'
    // $state (optional) - Comma-joined Document states to filter results. States should be 'completed', 'pending', 'expired'.
    //         ex. 'completed,pending'
    // $page (optional) - Page number offset. Default is 1.
    //         ex. 1
    // $perPage (optional) - number of result per page to return.
    //         Valid values are 10, 20, 30, 40, and 50. Default is 10.
    //         ex. 20
    // $recipientEmail (optional) = Narrow results to documents sent by RightSignature API User to the given Recipient Email.
    //         ex. 'a@abc.com'
    // $allDocs (optional) - Whether to show all documents from RightSignature Account. Should be 'true' or 'false'. 
    //         Default is 'false', and this is only available if the RightSignature API User is the account owner or an account admin.
    //         ex. 'false'
    function getDocuments($perPage = NULL, $page = NULL, $query = NULL, $template_id=NULL, $state=NULL) {
        $path = $this->api_url . "/documents";
        $params = array();
        // Combine arguments into URL parameters
        if (isset($query))
            $params['search'] = urlencode($query);
        if (isset($template_id))
            $params['template_id'] = urlencode($template_id);
        if (isset($state))
            $params['state'] = urlencode($state);
        if (isset($perPage))
            $params['per_page'] = urlencode($perPage);
        if (isset($page))
            $params['page'] = urlencode($page);
        $aResponse = $this->__apiCall($path,'GET',$params);
        return $aResponse;
    }
  
    // 
    // Returns xml response from RightSignature's Document Details call
    // 
    // Arguments:
    // $guid - RightSignature Document GUID
    //         ex. 'J1KHD2NX4KJ5S6X7S8'
    function getDocumentDetails($guid) {
        $path = $this->api_url . "/documents/".$guid;
        $aResponse = $this->__apiCall($path,'GET');
        return $aResponse;
    }
    // 
    // Returns xml response from RightSignature's list of Templates
    // 
    // Arguments:
    // $tags (optional) - associative array of tag names and values that are associated with the templates.
    //         ex. array('customized' => '', 'user id' => '123')
    // $query (optional) = Search term to narrow results. Should be URI encoded.
    //         ex. 'State Street'
    // $page (optional) - Page number offset. Default is 1.
    //         ex. 1
    // $perPage (optional) - number of result per page to return.
    //         Valid values are 10, 20, 30, 40, and 50. Default is 10.
    //         ex. 20
    function getTemplates($query = NULL, $perPage = NULL,$page = NULL) {
        $path = $this->api_url . "/reusable_templates";
        $params = array();
        // Combine arguments into URL parameters
        if (isset($query))
            $params['search'] = urlencode($query);
        if (isset($page))
            $params['page'] = urlencode($page);
        if (isset($perPage))
            $params['per_page'] = urlencode($perPage);
        
        $aResponse = $this->__apiCall($path,'GET',$params);
        return $aResponse;
    }
    // 
    // Gets Template XML from given guid
    // 
    function getTemplateDetails($guid){
        $path = $this->api_url . "/reusable_templates/".$guid;
        $aResponse = $this->__apiCall($path,'GET');
        return $aResponse;
    }
    // 
    // Generates a RightSignature Document from the given Template GUID and arguments
    // 
    // Arguments:
    // $guid - RightSignature's Template GUID
    //         ex. a_b2asc2a_123
    // $subject - Email subject for document
    // $roles - associative arrays of roles for document, locked will not allow the redirected user to modify the value.
    //         Formatted like [role_name => ['name' => Person's Name, 'email' => email, 'locked' => 'true' or 'false']].
    //         ex. array('Employee' => array('name' => 'john smith', 'email' => 'john@example.com', 'locked' => 'false'), 
    //                             'Owner' => array('name' => 'jane smith', 'email' => 'jane@example.com', 'locked' => 'true'))
    // $merge_fields - associative array of mergefields with properties, locked will not allow the redirected user to modify the value.
    //         Formatted like [merge_field_name => ['value' => value, 'locked' => 'true' or 'false']]
    //         ex. array('Address' => array('value' => '123 Maple Lane', 'locked' => 'false'), 
    //                             'User ID' => array('value' => '123', 'locked' => 'true'))
    // $tags (Optional) - associative array of tag names and values to associate with template. 
    //         ex. array('template' => '', 'user id' => '123', 'property' => '111')
    // $description (Optional) - description of document for signer to see
    // $callbackURL (Optional) - string of URL for RightSignature to POST document details to after Template gets created, viewed, and completed (all parties have signed). 
    //         Tip: add a unique parameter in the URL to distinguish each callback, like the template_id.
    //         NULL will use the default callback url set in the RightSignature Account settings page (https://rightsignature.com/oauth_clients).
    //         ex. 'http://mysite/document_callback.php?template_id=123'
    // $expires_in (Optional) - integer of days to expire document, allowed values are 2, 5, 15, or 30.
    function sendTemplate($iId, $sDocumentName, $merge_field_values, $aRoles, $iExpiresIn = 30, $sSharedWith = array(), $sMessage = NULL, $bInPerson = NULL, $sCallbackURL = NULL, $iPin = NULL, $aTags = array()) {
        $path = $this->api_url . '/reusable_templates/'.$iId.'/send_document';
        $aPostData = array();
        $aPostData['name'] = $sDocumentName;
        if (!empty($merge_field_values)) {
            $aMergeFlds = array();
            foreach($merge_field_values as $sTk => $mf) {
                $aMergeFlds[] = array(
                                    'id'=> $mf['id'],
                                    'value' => $mf['value']
                                );
            }
            if(!empty($aMergeFlds)){
                $aPostData['merge_field_values'] = $aMergeFlds;
            }
        }
        if (!empty($aRoles)) {
            $aRole = array();
            foreach($aRoles as $sk => $aRol) {
                $aRole[$sk]['name'] = $aRol['name'];
                //$aRole[$sk]['role_name'] = $aRol['name'];
                $aRole[$sk]['signer_name'] = $aRol['signer_name'];
                $aRole[$sk]['signer_email'] = $aRol['signer_email'];
                if(isset($aRol['signer_omitted']))
                    $aRole[$sk]['signer_omitted'] = $aRol['signer_omitted'];
                if(isset($aRol['is_sender']))
                    $aRole[$sk]['is_sender'] = $aRol['is_sender'];
                if(isset($aRol['sequence']))
                    $aRole[$sk]['sequence'] = $aRol['sequence'];
                if(isset($aRol['message']))
                    $aRole[$sk]['message'] = $aRol['message'];
            }
            if(!empty($aRole)){
                $aPostData['roles'] = $aRole;
            }
        }
        $aPostData['expires_in'] = $iExpiresIn;
        if(!empty($sSharedWith))
            $aPostData['shared_with'] = $sSharedWith;
        if(!empty($sMessage))
            $aPostData['message'] = $sMessage;
        if(!is_null($bInPerson))
            $aPostData['in_person'] = $bInPerson;
        if(!empty($aRol['pin']))
            $aPostData['pin'] = $iPin;
        if(!empty($aRol['tags']))
            $aPostData['tags'] = $aTags;
        if (!empty($sCallbackURL))
            $aPostData['callback_url'] = $sCallbackURL;
        // Send request to RightSignature.com API
        $options = array();
        $options['RequestContentType'] = 'application/json';//'application/x-www-form-urlencoded';
        $options['FailOnAccessError']= true;
        $aResponse = $this->__apiCall($path,'POST',$aPostData,$options);
        return $aResponse;
    }
    
    function testPost() {
        $path = $this->api_url . "/api/test.xml";
        $params = array();
        $xml = "<?xml version='1.0' encoding='UTF-8'?><testnode>Hello World!</testnode>";
        $params['xml']=$xml;
        $aResponse = $this->__apiCall($path,'POST',$params);
        return $aResponse;
    }
      
    function addUser($uname, $email) {
        //THIS EXAMPLE WORKS
        $path = $this->api_url . "/api/users.xml";
        $params = array();
        $xml = "<?xml version='1.0' encoding='UTF-8'?><user><name>$uname</name><email>$email</email></user>";

        //$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $path);
        //        $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
        //        $response = $this->httpRequest($path, $auth_header, "POST", $xml);
        //        return $response;

        //$params['user']['name']=$uname;
        //$params['user']['email']=$email;
        $params['xml']=$xml;
        $aResponse = $this->__apiCall($path,'POST',$params);
        return $aResponse;
    }
    function getUserDetails($aParams=array(),$aOption=array()){
        $path = $this->api_url . "/api/users/user_details.json";
        $aResponse = $this->__apiCall($path,'GET',$aParams,$aOption);
        return $aResponse;
    }
    function SendingRequestStatus($id){
        $path = $this->api_url . "/sending_requests/".$id;
        $aResponse = $this->__apiCall($path,'GET');
        return $aResponse;
    }
    function createSendingRequest($file_data=array(), $document_data=array(), $callback_url=null){
        $path = $this->api_url . "/sending_requests";
        $aPostData = array();
        
        if(!empty($file_data['name']))
            $aPostData['file']['name'] = $file_data['name'];
        if(!empty($file_data['source']))
            $aPostData['file']['source'] = $file_data['source'];
        else
            $aPostData['file']['source'] = 'upload'; //Must be one of: upload, remote_file.
        if(!empty($document_data['name']))
            $aPostData['document']['name'] = $document_data['name'];
        if(!empty($document_data['signer_sequencing']))
            $aPostData['document']['signer_sequencing'] = $document_data['signer_sequencing'];
        else
            $aPostData['document']['signer_sequencing'] = false;
        if(isset($document_data['personalized_messages']))
            $aPostData['document']['personalized_messages'] = $document_data['personalized_messages'];
        if(!empty($document_data['shared_with']))
            $aPostData['document']['shared_with'] = $document_data['shared_with'];
        if(isset($document_data['identity_method']))
            $aPostData['document']['identity_method'] = $document_data['identity_method'];
        if(!empty($document_data['callback_url']))
            $aPostData['document']['callback_url'] = $document_data['callback_url'];
        if(array_key_exists('api_embedded',$document_data)){
            if($document_data['api_embedded'] == true)
                $aPostData['document']['api_embedded'] = true;
            else
                $aPostData['document']['api_embedded'] = false;
        }
        
        if(isset($document_data['api_embed_width']))
            $aPostData['document']['api_embed_width'] = $document_data['api_embed_width'];
        if(isset($document_data['api_embed_height']))
            $aPostData['document']['api_embed_height'] = $document_data['api_embed_height'];
        if (!empty($document_data['roles'])) {
            $aRole = array();
            foreach($document_data['roles'] as $sk => $aRol) {
                $aRole[$sk]['name'] = $aRol['name'];
                $aRole[$sk]['signer_name'] = $aRol['signer_name'];
                $aRole[$sk]['signer_email'] = $aRol['signer_email'];
                if(isset($aRol['is_sender']))
                    $aRole[$sk]['is_sender'] = $aRol['is_sender'];
                if(isset($aRol['sequence']))
                    $aRole[$sk]['sequence'] = $aRol['sequence'];
                if(isset($aRol['message']))
                    $aRole[$sk]['message'] = $aRol['message'];
            }
            if(!empty($aRole)){
                $aPostData['document']['roles'] = $aRole;
            }
        }
        
        if(!empty($document_data['expires_in']))
            $aPostData['document']['expires_in'] = $document_data['expires_in'];
        else
            $aPostData['document']['expires_in'] = 30;
        if(isset($document_data['pin']))
            $aPostData['document']['pin'] = $document_data['pin'];
        if(isset($callback_url))
            $aPostData['callback_url'] = $callback_url; 
        $options = array();
        $options['RequestContentType'] = 'application/json';//'application/x-www-form-urlencoded';
        $options['FailOnAccessError']= true;
        $aResponse = $this->__apiCall($path,'POST',$aPostData,$options);
        return $aResponse;
    }
    
    function SendingRequest_UploadFile($sFilePathName,$sSendingRequest_id, $sSendingRequest_status, $sSendingRequest_upload_url,$sSendingRequest_document_template_id=null) {
        $aResponse = array(
                    'status' => false,
                    'err_msg' => ''
                    );
        $path = $sSendingRequest_upload_url;
        $url_path_str = $sSendingRequest_upload_url;
        $file_path_str = $sFilePathName;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_PUT, TRUE);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($api_request_parameters));
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/plain'));
        curl_setopt($ch, CURLOPT_URL, $url_path_str);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50000);
        //curl_setopt($ch, CURLOPT_HEADER, TRUE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $fh_res = fopen($file_path_str, 'r');
        curl_setopt($ch, CURLOPT_INFILE, $fh_res);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path_str));
        
        $api_response = curl_exec($ch);
        $api_response_info = curl_getinfo($ch);
        $sCurlError = curl_errno($ch) . '-' . curl_error($ch);
        fclose($fh_res);
        curl_close($ch);
        $api_response_header = trim(substr($api_response, 0, $api_response_info['header_size']));
        $api_response_body = substr($api_response, $api_response_info['header_size']);
        //$command = 'curl -v -X PUT -F "file=@'.$file_path_str.'" "'.$url_path_str.'"';
        if(!empty($api_response_info) && ($api_response_info['http_code'] == 200 || $api_response_info['http_code'] == '200') )
            $aResponse['status'] = true;
        else{
            $aResponse['status'] = false;
            $aResponse['err_msg'] = $sCurlError;
            $GLOBALS['log']->fatal("RightSignature: ".$sCurlError);
        }
        return $aResponse;
    }
    
    function SendingRequest_Uploaded($iId) {
        $path = $this->api_url . '/sending_requests/'.$iId.'/uploaded';
        $aPostData = array();
        $options = array();
        $options['RequestContentType'] = 'application/json';//'application/x-www-form-urlencoded';
        $options['FailOnAccessError']= true;
        $aResponse = $this->__apiCall($path,'POST',$aPostData,$options);
        return $aResponse;
    }
}
?>