<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class OpportunitiesViewDetail extends ViewDetail {

 	function __construct(){
 		parent::__construct();
 	}

    function OpportunitiesViewDetail(){
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if(isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        }
        else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


 	function display() {

	    $currency = new Currency();
	    if(isset($this->bean->currency_id) && !empty($this->bean->currency_id))
	    {
	    	$currency->retrieve($this->bean->currency_id);
	    	if( $currency->deleted != 1){
	    		$this->ss->assign('CURRENCY', $currency->iso4217 .' '.$currency->symbol);
	    	}else {
	    	    $this->ss->assign('CURRENCY', $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol());
	    	}
	    }else{
	    	$this->ss->assign('CURRENCY', $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol());
	    }
        
        //BEGIN: URDHVATECH
        $sSendAgreementCorporateSignBtn = '';
        require_once('modules/ACLActions/ACLAction.php');
        if(is_admin($current_user) || ACLAction::userHasAccess($current_user->id, 'UT_RightSignature', 'edit','module', true, true))
        {
            $sSendAgreementCorporateSignBtn = '<input id="agreementCorporateSignBtn" title="Send Corporate Agreement" detail_id ="'.$this->bean->id.'" class="button" type="button" name="agreementCorporateSignBtn" value="Send Corporate Agreement">';
        }
        $this->ss->assign('SENDAGREEMENTJOINTSIGNBTN', $sSendAgreementCorporateSignBtn);
$probability_script=<<<EOQ
<script>
    callbackCorporate = function(o){
        result = new Array();
        try {
            var data = eval("(" + o.responseText + ")");
            if(data.status == "success"){
                alert("The agreement has been sent for signature");
            }
            else{
                alert(data.message);
            }
        }
        catch(e) {
        }
        $.unblockUI();
    }
    
    $(document).on("click", "#agreementCorporateSignBtn", function() {
        sDetailId = $(this).attr('detail_id');
        if(confirm('This action would send the document for signature. Do you want to continue?')){
            $('#rs_corporate_modal').modal('hide');
            $.blockUI({ message: '<h1>Please wait...</h1>' });
            postData = "sugar_body_only=1&to_pdf=1&module=Opportunities&action=sendAgreement&record="+sDetailId;
            var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php',{success: callbackCorporate, failure: callbackCorporate}, postData);
        }
    });
</script>
EOQ;
        //ENDS: URDHVATECH
        
		$this->ss->assign('rowCountEdit', $this->bean->totalCountRow);
        $this->ss->assign('dataRowEdit', $this->bean->rowData);

		$rownCount = 1;
		$optionOutSourced = $GLOBALS['app_list_strings']['outsourced_dom'];
		foreach ($optionOutSourced as $index => $value){
		$arrayDom .= "<option value=$index>$value</option>";
			
		}
        $this->ss->assign('outSourceDom', $arrayDom);
        $this->ss->assign('detailView', $_GET['action']);
		echo '<script type="text/javascript" src="custom/modules/Opportunities/js/panelHide.js"></script>';
		
 		parent::display();
        echo $probability_script;
 	}
}
?>