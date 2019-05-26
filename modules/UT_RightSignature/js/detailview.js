/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
function update_rs_status(record_id) {
    var callback = {
        success:function(o){
            var resp = YAHOO.lang.JSON.parse(o.responseText);
            alert(resp);
            SUGAR.ajaxUI.hideLoadingPanel();
            location.reload();
        }
    }
    SUGAR.ajaxUI.showLoadingPanel();
    YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=UT_RightSignature&action=ajax_handler&to_pdf=1&id='+record_id, callback, null);
    return false;
}