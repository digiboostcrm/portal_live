$(document).ready(function(){
    
    callbackIndividual = function(o){
        result = new Array();
        try {
            var data = eval("(" + o.responseText + ")");
            if(data.status == "success"){
            }
            else{
                alert(data.message);
            }
        }
        catch(e) {
            alert('There was an error handling this request.');
        }
        $.unblockUI();
    }
        
    $(document).on("click", "#agreementIndividualSignBtn", function() {
        sDetailId = $(this).attr('detail_id');
        if(confirm('This action would send the document for signature. Do you want to continue?')){
            $.blockUI({ message: '<h1>Please wait...</h1>' });
            postData = "sugar_body_only=1&to_pdf=1&module=Contacts&action=sendAgreement&record="+sDetailId+"&for=individual";
            var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php',{success: callbackIndividual, failure: callbackIndividual}, postData);
        }
    });
    $(document).on("click", "#agreementJointSignBtn", function() {
        sDetailId = $(this).attr('detail_id');
        if(confirm('This action would send the document for signature. Do you want to continue?')){
            $.blockUI({ message: '<h1>Please wait...</h1>' });
            postData = "sugar_body_only=1&to_pdf=1&module=Contacts&action=sendAgreement&record="+sDetailId+"&for=joint";
            var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php',{success: callbackIndividual, failure: callbackIndividual}, postData);
        }
    });
});