<?php

require_once('include/SugarPHPMailer.php');

class DigiboostMailer{
	
	const TEMPLATE_VERSION = "v1.0";
	const TEMPLATE_FILE = "mailerTemplate.txt";
	
	/**
	 * Used to store base path for the template
	 **/
	private $tempatePath;
	
	/**
	 * Used to store the replacement vars
	 **/
	private $templateVars;
	
	/**
	 * Used to store base template content
	 **/
	private $templateBase;
	
	/**
	 * Used to store the sending mail template content
	 **/
	private $templateContent;
	
	/**
	 * Initialize template path and base template
	 **/
	public function __construct(){
		$this->tempatePath = dirname(__FILE__)."/templates/" . self::TEMPLATE_VERSION;
		
		$this->templateBase = $this->parseTemplate(self::TEMPLATE_FILE);
	}
	
	private function parseTemplate($sTemplateFile){
		$sTemplateFullPath = "{$this->tempatePath}/{$sTemplateFile}"; 
		if(!file_exists($sTemplateFullPath) || !is_file($sTemplateFullPath)){
			throw new \Exception("Invalid email template provided");
		}
		
		return file_get_contents($sTemplateFullPath);
	}
	
	/**
	 * This function is used to get html content for email
	 * @param array $aParams - replace parameters for template
	 * @param string $sTemplate - file of the template
	 * @return html message
	 **/
	private function getHTMLMessage($aParams, $sTemplate){
		$this->templateContent = $this->parseTemplate($sTemplate);
		
		$this->updateTemplateVars($aParams);
		
		return $this->getFinalMessage();
	}
	
	private function updateTemplateVars($aParams){
		if(!empty($aParams) && !empty($this->templateContent)){
			foreach($aParams as $sKey => $sValue){
				$this->templateContent = str_replace('{'.$sKey.'}', $sValue, $this->templateContent);
			}
		}
	}
	
	private function getFinalMessage(){
		return str_replace('{mail_body}', $this->templateContent, $this->templateBase);
	}
	
	/**
	 * This function is used to send email with the content
	 * @param string $sTemplate - file of the template
	 * @param array $aParams - replace parameters for template
	 * @param string $sSubject - Subject for the email
	 * @param mixed $aToAddress - one or more to address for the email
	 **/
	public function sendEmail($sTemplate, $aParams, $sSubject, $aToAddress){
		$this->validateEmailParams($sTemplate, $aParams, $sSubject, $aToAddress);
		$oMailer = $this->getMailer();
		
		$oMailer->Subject = $sSubject;
		$oMailer->Body_html = $this->getHTMLMessage($aParams, $sTemplate);
		$oMailer->Body = wordwrap($oMailer->Body_html, 90000);
		$oMailer->isHTML(true);
		
		if(!is_array($aToAddress)){
			$aToAddress = array($aToAddress);
		}
		
		//$aToAddress = array("mathicse47@gmail.com");
		
		foreach($aToAddress as $sEmailAddress){
			if(filter_var($sEmailAddress, FILTER_VALIDATE_EMAIL)){
				$oMailer->AddAddress($sEmailAddress);
			}
		}
		
		@$oMailer->Send();
	}
	
	private function getMailer(){
		$oOutboundEmail = new OutboundEmail();
        $oMailSettings = $oOutboundEmail->getSystemMailerSettings();
		$oMailer = new SugarPHPMailer(); 
		$oMailer->setMailerForSystem(); 
		$oMailer->From = $oMailSettings->mail_smtpuser; 
		$oMailer->FromName = $oMailSettings->smtp_from_name; 
		return $oMailer;
	}
	
	private function validateEmailParams($sTemplate, $aParams, $sSubject, $aToAddress){
		if(empty($aToAddress)){
			throw new \Exception("No email address provided");
		}
		
		if(empty($sSubject)){
			throw new \Exception("No subject provided");
		}
		
		if(empty($sTemplate)){
			throw new \Exception("No template provided");
		}
	}
	
	public function getTicketReceiverByCategory($sCategory){
		$sCategory = strtoupper($sCategory);
		
		$aMailConfig = array(
			'ACCOUNT MANAGEMENT' => array(
				'name' => 'Accounts team',
				'email' => 'am@digiboost.com'
			),
			'BILLING' => array(
				'name' => 'Billing team',
				'email' => 'billing@digiboost.com'
			),
			'SUPPORT' => array(
				'name' => 'Support team',
				'email' => 'support@digiboost.com'
			),
			'MARKETING' => array(
				'name' => 'Marketing team',
				'email' => 'marketing@digiboost.com'
			),
			'PROJECT MANAGER' => array(
				'name' => 'Project team',
				'email' => 'Pm@digiboost.com'
			)
		);
		
		return isset($aMailConfig[$sCategory]) ? $aMailConfig[$sCategory] : array(
			'name' => 'Digiboost Developer',
			'email' => 'mathicse47@gmail.com'
		);
	}
	
	public function getTicketPriorityText($sPriority){
		$aPriorities = array(
			'P1' => 'High',
			'P2' => 'Medium',
			'P3' => 'Low',
		);
		
		return isset($aPriorities[$sCategory]) ? $aPriorities[$sPriority] : 'Normal';
	}
}

