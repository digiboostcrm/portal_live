<input type="hidden" id = 'quickbooks_menu_url' value='{$quickbooks_menu_url}'>
<input type="hidden" id = 'quickbooks_oauth_url' value='{$quickbooks_oauth_url}'>
{literal}
<style>
#box-table-a {
    border-collapse: collapse;
    font-family: "Lucida Sans Unicode","Lucida Grande",Sans-Serif;
    font-size: 12px;
    margin: 20px;
    text-align: center;
    width: 350px;
}
#box-table-a th {
    background: none repeat scroll 0 0 ;
    border-bottom: 1px solid #FFFFFF;
/*    border-top:  ;*/
/*    color: #003399;*/
    font-size: 13px;
    font-weight: normal;
    padding: 8px;
}

.sync-label {
    cursor: pointer;
}

#box-table-a td {
    background: none repeat scroll 0 0;
    border-bottom: 1px solid #FFFFFF;
    border-top: 1px solid transparent;
/*    color: #666699;*/
    text-align:center;
    padding: 8px;
}

.leftMenu	{
	background-color: #EFECEC;
	float:left;
	width:150px;
}


.success {
    background-color: #E0F2C7;
    border: 1px solid #CDEBA4;
    color: #6EA800;
}

.failure {
    background-color: #d9534f;
    border: 1px solid #CDEBA4;
}

.message {
    border: 1px solid #D2D1D1;
    border-radius: 3px 3px 3px 3px;
    color: #5D5D5D;
    font-weight: bold;
    letter-spacing: 1px;
    margin-bottom: 15px;
    padding: 14px;
    position: relative;
}

#MANUAL_SHOW > div {
    float: left;
}

.leftMenuItems	{
    line-height: 2em;
    margin-right: 10px;
    overflow: hidden;
    padding-left: 20px;
    position: relative;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.mainQBTiger	{
	width:100%;
}

#QBResult
{
	font-size: 12px;
}

#APIDETAILS > div {
    float: right;
    padding-top: 30px;
}

.QBSelected
{
    float: left;
    padding: 12px;
    width:150px;
    height:15px;
    cursor: pointer;
    background-color: #999999;
    color: #444444;
}

.QBBold
{
	font-weight: bold;
	background-color:#E7E7E7;
}

.loadingGif
{
	display:none;
}

.listRowInfo    {
    font-size: 10px;
}

.left_sidebar_link	{
	margin-bottom: 5px;
	cursor: pointer;
	padding: 5% 0 0 11%;
	height: 28px;
	border-bottom: 1px solid #dedede;
}

.left_sidebar_link:hover	{
	background-color: #dedede;
}

.QBNonSelected
{
    float: left;
    padding: 12px;
    width:150px;
    height:15px;
    cursor: pointer;
    background-color: #EFECEC;
    color: #444;
}

#QBNone
{
	float:left;
	padding:5px;
}
</style>
<script type="text/javascript" src="https://appcenter.intuit.com/Content/IA/intuit.ipp.anywhere.js"></script>
<script type="text/javascript">
var quickbooks_menu_url = $('#quickbooks_menu_url').val();
var quickbooks_oauth_url = $('#quickbooks_oauth_url').val();
intuit.ipp.anywhere.setup({
menuProxy: quickbooks_menu_url,
grantUrl: quickbooks_oauth_url
});
</script>


<script type = "text/javascript">
function showCC(checked)	{
	if(checked == true)
		document.getElementById('bccdiv').style.display = "block";
	else if(checked == false)
		document.getElementById('bccdiv').style.display = "none";

}

function qbshowmessage(msg, msgclass)	{
	$('#showmessage').show();
	$('#showmessage').width('220px');
	$('#showmessage').addClass("message failure");
	$('#showmessage').html(msg);
}

function clearQBResults()
{
	$('#vtiger_create').html('0');
	$('#vtiger_update').html('0');
	$('#vtiger_fail').html('0');
	$('#qb_update').html('0');
	$('#qb_create').html('0');
	$('#qb_fail').html('0');
}

function callService(service, buttonName)
{
    var sync_up_to_date_message = 'Sync is up to date';
	var mode, data, recordcount, rundata, i, maxcount = 5, startposition = 0, modvalue, finalcall = false, msg, source = 'suite', url_data;
	var quickbooksModule = Array('Invoice', 'Customer', 'Item', 'SalesOrder', 'Quotes', 'Vendor');
	document.getElementById("qbLiveResult").innerHTML = "";
	document.getElementById("loadingImage_"+buttonName).style.display = "block";
	clearQBResults();
	if(service == 'Invoice' || service == 'SalesOrder' || service == 'Quotes' || service == 'QBInvoice' || service == 'QBSalesOrder' || service == 'QBQuotes')
		maxcount = 2;
	else
		maxcount = 5;

	if(js_in_array(service, quickbooksModule))	{
		source = 'quickbooks';
		// change startposition to 1 if we are going to fetch records from quickbooks
		if(startposition == 0)
			startposition = 1;

	}
    else {
        sync_up_to_date_message = 'Synced all modified records';
    }


	mode = 'edit';
	// record count to edit
	url_data = 'index.php?module=qbs_QBSugar&action=GetCount&service='+service+'&mode='+mode+'&to_pdf=1';
	$.ajax({
                url: url_data,
                type: "POST",
                async: false,
                success:function(data) {
                       recordcount = parseInt(data);
                }
        });

	// going to send records which are updated after last sync
	var url_data = 'index.php?module=qbs_QBSugar&action=Sync&service='+service+'&startposition='+startposition+'&maxresult='+maxcount+'&finalcall='+finalcall+'&mode='+mode+'&to_pdf=1';
	if(recordcount != 'NaN' && recordcount > 0 && recordcount != null)
	{
		finalcall = false;
		modvalue = recordcount / maxcount;
		modvalue = Math.ceil(modvalue);
		for(i = 1; i <= modvalue ;i ++)
		{
			// set finalcall to true if i and modvalue is equal
			if(i == modvalue)
				finalcall = true;

			url_data = 'index.php?module=qbs_QBSugar&action=Sync&service='+service+'&startposition='+startposition+'&maxresult='+maxcount+'&finalcall='+finalcall+'&mode='+mode+'&to_pdf=1';

			$.ajax({
				url: url_data,
				type: "POST",
				async: false,
				success:function(data) {
					var encoded_data = JSON.parse(data);
                                        if(encoded_data != null)        {
                                                // show result
                                                showQBResult(encoded_data.success.create, encoded_data.success.edit, encoded_data.failure.create, encoded_data.failure.edit, source);
						if(encoded_data.content == null)
							encoded_data.content = 'No  record present in '+service+' to edit';

                                                // show live result
                                                $('#qbLiveResult').append(encoded_data.content+'<br>');
                                        }
             			}
			});
			// incrementing start position
                        startposition = startposition + maxcount;
		}
	}
	else
	{
                $('#qbLiveResult').append('<div class = "row sync-result-row"> ' + sync_up_to_date_message + ' - '+service+' ('+source+') </div>'); // Edit mode

	}

	// no need to pass if service is for quickbooks
	if(js_in_array(service, quickbooksModule))	{
		document.getElementById("loadingImage_"+buttonName).style.display = "none";
		return false;
	}

	// going to send records which are new in vtiger and not created in eol
	mode = 'create';
        // record count to edit
        url_data = 'index.php?module=qbs_QBSugar&action=GetCount&service='+service+'&mode='+mode+'&to_pdf=1';
        $.ajax({
                url: url_data,
                type: "POST",
                async: false,
                success:function(data) {
                	recordcount = parseInt(data);
                }
        });

	startposition = 0;
	if(recordcount != 'NaN' && recordcount > 0)
	{
		finalcall = false;
                modvalue = recordcount / maxcount;
                modvalue = Math.ceil(modvalue);
		// set finalcall to true if i and modvalue is equal
		for(i = 1; i <= modvalue; i ++)
                {
                        if(i == modvalue)
                                finalcall = true;

                        url_data = 'index.php?module=qbs_QBSugar&action=Sync&service='+service+'&startposition='+startposition+'&maxresult='+maxcount+'&finalcall='+finalcall+'&mode='+mode+'&to_pdf=1';

                        $.ajax({
                                url: url_data,
                                type: "POST",
                                async: false,
                                success:function(data) {
                                        var encoded_data = JSON.parse(data);
                                        if(encoded_data != null)        {
                                                // show result
                                                showQBResult(encoded_data.success.create, encoded_data.success.edit, encoded_data.failure.create, encoded_data.failure.edit, source);
						if(encoded_data.content == null)
                                                        encoded_data.content = 'No  record present in '+service+' to create';

                                                // show live result
                                                $('#qbLiveResult').append(encoded_data.content+'<br>');
                                        }
                                }
                        });
                        // incrementing start position
                        startposition = startposition + maxcount;
		}
	}
	else
	{
		$('#qbLiveResult').append('No record present in '+service+' to create <br>');
	}
	document.getElementById("loadingImage_"+buttonName).style.display = "none";
}

function getpreviouscountvalue(divid)	{
	p_value = $("#"+divid).text();
	return p_value;
}

function showQBResult(createcount, editcount, failcreate, failedit, source)	{
	var failcount  = parseInt(failcreate) + parseInt(failedit);
	if(source == 'suite')	{
		var p_qbcreate = getpreviouscountvalue("qb_create");
		var p_qbedit = getpreviouscountvalue("qb_update");
		var p_qbfail = getpreviouscountvalue("qb_fail");

		var qbcreate = parseInt(p_qbcreate) + parseInt(createcount);
		var qbedit   = parseInt(p_qbedit) + parseInt(editcount);
		var qbfail   = parseInt(p_qbfail) + parseInt(failcount);

		$('#qb_create').html(qbcreate);
		$('#qb_update').html(qbedit);
		$('#qb_fail').html(qbfail);
	}
	else if(source == 'quickbooks')	{
		var p_vtcreate = getpreviouscountvalue("vtiger_create");
		var p_vtedit = getpreviouscountvalue("vtiger_update");
		var p_vtfail = getpreviouscountvalue("vtiger_fail");

		var vtcreate = parseInt(p_vtcreate) + parseInt(createcount);
                var vtedit   = parseInt(p_vtedit) + parseInt(editcount);
                var vtfail   = parseInt(p_vtfail) + parseInt(failcount);

		$('#vtiger_create').html(vtcreate);
		$('#vtiger_update').html(vtedit);
		$('#vtiger_fail').html(vtfail);
	}
}

function js_in_array(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

function resetQBTiger()
{
	var confirm_reset = confirm('Are you sure you want to reset Sync Details ?');
	if(!confirm_reset)
		return false;

	document.getElementById("loadingImage_resetQBTiger").style.display = "block";
	var url_data = 'index.php?module=qbs_QBSugar&action=resetQBTiger&to_pdf=1';
        $.ajax({
                url: url_data,
                type: "POST",
                async: false,
                success:function(data) {
			var parsed_data = JSON.parse(data);
                        document.getElementById("showmessage").style.display = "block";
                        document.getElementById("showmessage").style.visibility = "visible";
                        if(parsed_data.class_msg == 'SUCCESS')
                        {
                                document.getElementById("showmessage").innerHTML = parsed_data.msg+'<a href="javascript:closediv(\'showmessage\');" style = "float:right"> <img border="0" align="absmiddle" src="themes/default/images/close.gif" alt="Close" title="Close">  </a>';
                        }
                        else
                        {
                                document.getElementById("showmessage").style.background = "#D9534F";
                                document.getElementById("showmessage").style.color = "white";
                                document.getElementById("showmessage").innerHTML = parsed_data.msg+'<a href="javascript:closediv(\'showmessage\');" style = "float:right"> <img border="0" align="absmiddle" src="themes/default/images/close.gif" alt="Close" title="Close">  </a>';
                        }
                        document.getElementById("loadingImage_resetQBTiger").style.display = "none";
                }
        });
}

function closediv(divid)
{
	$('#'+divid).hide();
}

function deleteQueue(service, buttonName)
{
	document.getElementById("loadingImage_"+buttonName).style.display = "block";
	var url_data = 'index.php?module=qbs_QBSugar&action=DeleteQueue&service='+service+'&mod='+buttonName+'&to_pdf=1';
        $.ajax({
                url: url_data,
                type: "POST",
                async: false,
                success:function(data) {
			document.getElementById('showmessage').style.visibility = "visible";
                        document.getElementById('button_'+buttonName+'_delete').style.display = "none";
                        document.getElementById("loadingImage_"+buttonName).style.display = "none";
                        document.getElementById("button_"+buttonName).style.display = "block";
                        document.getElementById("showmessage").style.display = "block";
                        document.getElementById("showmessage").innerHTML = data+'<a href="javascript:closediv(\'showmessage\');" style = "float:right"> <img border = "0" align = "absmiddle" src="themes/default/images/close.gif" alt = "Close" title = "Close">  </a>';
                }
        });
}

function addQueue(service, buttonName)	
{
	document.getElementById("loadingImage_"+buttonName).style.display = "block";
	var url_data = 'index.php?module=qbs_QBSugar&action=AddQueue&service='+service+'&mod='+buttonName+'&to_pdf=1';
        $.ajax({
                url: url_data,
                type: "POST",
                async: false,
		success:function(data) {
			if(data == 'Service Added To Queue')
			{
				document.getElementById('showmessage').style.visibility = "visible"
				document.getElementById('button_'+buttonName).style.display = "none";
				document.getElementById('button_'+buttonName+'_delete').style.display = "block";
			}
			document.getElementById("loadingImage_"+buttonName).style.display = "none";
			document.getElementById("showmessage").style.display = "block";
			document.getElementById("showmessage").innerHTML = "<span style = 'margin-left:25%'>"+data+'</span><a href="javascript:closediv(\'showmessage\');" style = "float:right"> <img border="0" align="absmiddle" src="themes/default/images/close.gif" alt="Close" title="Close"> </a>';
		}
        });
}

function redirect(url)	{
	window.location.href = url;
}

function redirectTo(action)	{
	window.location.href = ''
}

function checkForm()
{
	var action = document.getElementById('qbaction').value;
	if(action == 'Edit')
	{
		return true;
	}
	var days = document.getElementById('days').value;
	var op = isNaN(days);
	if(op == true) { alert("Days must be numeric");return false; }
	
}
</script>
{/literal}
<br>
{if $smarty.request.msg eq 5}
<div class = "message success" style = "padding-left: 5%;width:400px;margin-left: 300px;" id = 'showmessage'> Successfully saved Quickbooks api details. <a href="javascript:closediv('showmessage');" style = "float:right"> <img border="0" align="absmiddle" src="themes/default/images/close.gif" alt="Close" title="Close"> </a></div>
{/if}
<div class = "message success" style = "padding-left: 5%;width:400px;margin-left: 300px;display:none" id = "showmessage">
</div>
{if $status}
<div class="row" style="float:right;width:25%"> 
    <label> <h4> Status: </h4> </label> 
    <span class="label label-success"> Connected </span> 
</div>
<br><br>
<div class="row" style="position:relative;float:right;right:-10%;">
    <label> <h5> Company: </h5> </label>
    <span style='text-decoration: underline;color:#F08377'><b> {$quickbooks_company_name}</b> </span>
</div>

<form action="#" method="post" name="qbtigersettings">
<div class = "mainQBTiger">
	<div id = "MANUAL_SHOW" style = "display:{$manual_show};float:left;width:100%">  
		<div style = "display:{$manual_show};float:left;width:60%;" class = "edit search basic">
		<div class = 'widget_header row-fluid'>
	                <div class = 'span1'> 
				<img width="48" height="48" border="0" title="qbtiger" alt="qbtiger" src="modules/{$currentModule}/images/qbtiger.png" style="float:left;padding: 5px;"> 
	        	        <div class = "span8" style="float:left;padding: 5px;"> <h3> Smart Sync </h3> <i>User can Sync Modules To/From QuickBooks</i> </div>
			</div>
		</div>
                <table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">
			<tr>
				<td width = "180"> 
                            <div class = 'sync-label' data-id = 'get_contacts' title=' Last Synced: {$last_synced.Customer}'> Get Contacts From QuickBooks </div>
				</td>
				<td width = "10">
					 <div id = "loadingImage_getContactsfromQB" class = "loadingGif" style = "float:right;"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div>
				</td>
				<td width = "30">
					<div id = "button_getContactsfromQB" style = "display:{$Customer}">
						<input type = "button" class = 'button' name = "getContactsfromQB" id = "getContactsfromQB" value = "Sync" onclick = "callService('Customer','getContactsfromQB')">
	 					<input type = "button" class = 'button' value = "Add To Queue" onclick = "addQueue('Customer', 'getContactsfromQB')"> 
					</div>
					<div id = "button_getContactsfromQB_delete" style = "display:{$Customer_delete}">
	 					<input type = "button" class = 'button' value = "Delete from Queue" onclick = "deleteQueue('Customer', 'getContactsfromQB')"> 
					</div>
                                </td>
			</tr>
			<tr>
				<td width = "180"> 
                            <div class = 'sync-label' data-id = 'send_contacts' title='Last Synced: {$last_synced.QBCustomer}'> Send Contacts to QuickBooks </div>
				</td>
                                <td width = "10">
               		                 <div id = "loadingImage_sendContactstoQB" class = "loadingGif" style = "float:right;"><img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div>
				</td>
				<td width = "30">
					<div id = "button_sendContactstoQB" style = "display:{$QBCustomer}"> 
						<input type = "button" class = 'btn' name = "sendContactstoQB" id = "sendContactstoQB" value = "Sync" onclick = "callService('QBCustomer','sendContactstoQB')" >
	                                        <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('QBCustomer', 'sendContactstoQB')"> 
					</div>
					<div id = "button_sendContactstoQB_delete" style = "display:{$QBCustomer_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('QBCustomer', 'sendContactstoQB')"> 
                                        </div>
				</td>
			</tr>
			<tr>
				<td width = "180"> 
                            <div class = 'sync-label' data-id = 'get_invoice' title='Last Synced: {$last_synced.Invoice}'> Get Invoice From QuickBooks </div>

				</td>
				<td width = "10">
                                        <div id = "loadingImage_getInvoicefromQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading" > </div>
				</td>
				<td width = "30">
					<div id = "button_getInvoicefromQB" style = "display:{$Invoice}"> 
						<input type = "button" class = 'btn' name = "getInvoicefromQB" id = "getInvoicefromQB" value = "Sync" onclick = "callService('Invoice','getInvoicefromQB')">
                                                <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('Invoice', 'getInvoicefromQB')"> 
					</div>
					<div id = "button_getInvoicefromQB_delete" style = "display:{$Invoice_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('Invoice', 'getInvoicefromQB')"> 
                                        </div>
                                </td>
			</tr>
			<tr>
				<td width = "180"> 
                            <div class = 'sync-label' data-id = 'send_invoice' title='Last Synced: {$last_synced.QBInvoice}'> Send Invoice to QuickBooks </div>
				</td>
                                <td width = "10">
                	                <div id = "loadingImage_sendInvoicetoQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div>
                                </td>
				<td width = "30">
					<div id = "button_sendInvoicetoQB" style = "display:{$QBInvoice}"> 
						<input type = "button" class = 'btn' name = "sendInvoicetoQB" id = "sendInvoicetoQB" value = "Sync" onclick = "callService('QBInvoice','sendInvoicetoQB')" >
                                                <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('QBInvoice', 'sendInvoicetoQB')"> 
					</div>
					<div id = "button_sendInvoicetoQB_delete" style = "display:{$QBInvoice_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('QBInvoice', 'sendInvoicetoQB')"> 
                                        </div>
                                </td>
			</tr>
			<!--smackservices -->
			
			<tr>
				<td width = "180"> 
                            <div class = 'sync-label' data-id = 'get_SalesOrder' title='Last Synced: {$last_synced.SalesOrder}'> Get SalesRecipt From QuickBooks </div>

				</td>
				<td width = "10">
                                        <div id = "loadingImage_getSalesOrderfromQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading" > </div>
				</td>
				<td width = "30">
					<div id = "button_getSalesOrderfromQB" style = "display:{$SalesOrder}"> 
						<input type = "button" class = 'btn' name = "getInvoicefromQB" id = "getInvoicefromQB" value = "Sync" onclick = "callService('SalesOrder','getSalesOrderfromQB')">
                                                <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('SalesOrder', 'getSalesOrderfromQB')"> 
					</div>
					<div id = "button_getSalesOrderfromQB_delete" style = "display:{$SalesOrder_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('SalesOrder', 'getSalesOrderfromQB')"> 
                                        </div>
                                </td>
			</tr><!--/smackservices-->
			<tr>
                                <td width = "180">
                            <div class = 'sync-label' data-id = 'get_quotes' title=' Last Synced: {$last_synced.Quotes}'> Get Quotes From QuickBooks </div>

                                </td>
			         <td width = "10">
         	                        <div id = "loadingImage_getQuotesfromQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading" > </div>
                                 </td>
                                 <td width = "30">
					<div id = "button_getQuotesfromQB" style = "display:{$Quotes}">
                                               	<input type = "button" class = 'btn' name = "getQuotesfromQB" id = "getQuotesfromQB" value = "Sync" onclick = "callService('Quotes','getQuotesfromQB')">
	                                        <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('Quotes', 'getQuotesfromQB')"> 
					</div>
					<div id = "button_getQuotesfromQB_delete" style = "display:{$Quotes_delete}">
	                                        <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('Quotes', 'getQuotesfromQB')"> 
	                                 </div>
                                  </td>
                          </tr>
                          <tr>
                                  <td width = "180">
                            <div class = 'sync-label' data-id = 'send_quotes' title=' Last Synced: {$last_synced.QBQuotes}'> Send Quotes to QuickBooks </div>
                                  </td>
				<td width = "10">
        	                        <div id = "loadingImage_sendQuotestoQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div>
                                </td>
                                <td width = "30">
					<div id = "button_sendQuotestoQB" style = "display:{$QBQuotes}"> 
                                                <input type = "button" class = 'btn' name = "sendQuotestoQB" id = "sendQuotestoQB" value = "Sync" onclick = "callService('QBQuotes','sendQuotestoQB')" >
                                                <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('QBQuotes', 'sendQuotestoQB')"> 
					</div>
					<div id = "button_sendQuotestoQB_delete" style = "display:{$QBQuotes_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('QBQuotes', 'sendQuotestoQB')"> 
                                        </div>
                                        </td>
                          </tr>
	 		<tr>
					<td width = "180"> 
                            <div class = 'sync-label' data-id = 'get_item' title='Last Synced: {$last_synced.Item}'> Get Products From QuickBooks </div>
					</td>
                                        <td width = "10">
                                                <div id = "loadingImage_getProductsfromQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div>
                                        </td>
					<td width = "30"> 
					<div id = "button_getProductsfromQB" style = "display:{$Item}"> 
						<input type = "button" class = 'btn' name = "getProductsfromQB" id = "getProductsfromQB" value = "Sync"  onclick = "callService('Item','getProductsfromQB')">
                                                <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('Item', 'getProductsfromQB')"> 
					</div>
					<div id = "button_getProductsfromQB_delete" style = "display:{$Item_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('Products', 'getProductsfromQB')"> 
                                        </div>
                                        </td>
			</tr>
			<tr>
					<td width = "180"> 
                            <div class = 'sync-label' data-id = 'send_item' title='Last Synced: {$last_synced.QBItem}'> Send Products to QuickBooks </div>
					</td>
					<td width = "10">
						<div id = "loadingImage_sendProductstoQB" class = "loadingGif"> <img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div>
					</td>
					<td width = "30">
					<div id = "button_sendProductstoQB" style = "display:{$QBItem}"> 
						<input type = "button" class = 'btn' name = "sendProductstoQB" id = "sendProductstoQB" value = "Sync"  onclick = "callService('QBItem','sendProductstoQB')" >
                                                <input type = "button" class = 'btn' value = "Add To Queue" onclick = "addQueue('QBItem', 'sendProductstoQB')"> 
					</div>
					<div id = "button_sendProductstoQB_delete" style = "display:{$QBItem_delete}">
                                                <input type = "button" class = 'btn' value = "Delete from Queue" onclick = "deleteQueue('QBItem', 'sendProductstoQB')"> 
                                        </div>
                                       </td>
			</tr>
		</table>
		</div>
		<div class='span5'>
                                <div class = 'widget_header row-fluid' style = 'padding-top: 25px;'> <h2> <strong> Sync Result </strong> </h2> </div> <hr>

			<div id = "QBResult" style="color: default;font-size: medium;">
				<table id="box-table-a" class='list table-responsive'>
					<thead><tr> <th> # </th> <th> Created </th> <th> Updated </th> <th> Failed </th> </tr></thead>
					<tbody style='height:70px;'><tr> <td> SuiteCRM </td> <td> <span id = "vtiger_create"> 0  </span> </td> <td> <span id = "vtiger_update"> 0 </span> </td> <td> <span id = "vtiger_fail"> 0 </span> </td> </tr>
					<tr> <td> QuickBooks </td> <td> <span id = "qb_create"> 0 </span> </td> <td> <span id = "qb_update"> 0 </span> </td> <td> <span id = "qb_fail"> 0 </span> </td> </tr></tbody>
				</table> 
			</div>
                <div id = "qbLiveResult" style="color:black;height:325px;width:380px;overflow:auto;padding:15px;"></div>
<br/>
		</div>
        {else}
        <h4>SuiteCRM QuickBooks Connector</h4>
        <hr class = 'hr-dotted'>
        <div style="border: 2px solid #F08377;text-align: center; padding: 8px;">
        Welcome! SuiteCRM not connected to Quickbooks yet. You are either connecting to Quickbooks for the first time or your current connection has expired.<b> Click below button </b>to authenticate to Quickbooks.<br>
        <br>
        <ipp:connectToIntuit></ipp:connectToIntuit>
        <br>
        </div>

        {/if}
        
	</div>
	<div id = "API_SHOW" style = "display:{$api_show};float:left;width:800px;padding-left:20px;" > <br>
		<div class = 'widget_header row-fluid'>
                	<div class = 'span1'> <img width="48" height="48" border="0" title="qbtiger" alt="qbtiger" src="modules/{$currentModule}/images/qbtiger.png"> </div>
	                <div class = "span8"> <h3> API Details </h3> </div>
        	        <div class = "span3">
                                {if $MODE eq 'EDIT'}
                                        <input type = "submit" value = "Save" id = "qbaction" name = "qbaction" class="btn">
                                {else}
                                        <input type = "submit" value = "Edit" id = "qbaction" name = "qbaction" class="btn">
                                {/if}
        		 </div>
		        <div class = "span8"> <i> Save / Edit QuickBooks App Token, Consumer Key and Consumer Secret </i> </div>
		</div>
                <table border=0 cellspacing=0 cellpadding=5 width=100% class="table table-bordered table-condensed listViewEntriesTable">
                        <tr class = 'listViewHeaders'>
                                <th class="listViewEntryValue medium"> <strong> QuickBooks(Online) API Details </strong> </th>
                        </tr>
                </table>
                <table border=0 cellspacing=0 cellpadding=5 width=100% class="table table-bordered table-condensed listViewEntriesTable">
                        <tr>
                                <td class="small cellText big" width="30%" ><strong> App Token </strong></td>
                                {if $MODE eq 'EDIT'}
                                       <td class = "small cellText" width = "70%"> <input type = "text" name = "app_token" id = "app_token" value = "{$APP_TOKEN}" class="input-xlarge" style = 'width:320px;'> </td>
                                {else}
                                        <td class="small cellText" width="70%"> {$APP_TOKEN} </td>
                                {/if}
                        </tr>
                        <tr>
                                <td class="small cellText big" width="30%" ><strong> Consumer Key </strong></td>
                                {if $MODE eq 'EDIT'}
                                       <td class = "small cellText" width = "70%"> <input type = "text" name = "consumer_key" id = "consumer_key" value = "{$CONSUMER_KEY}" class="input-xlarge" style = 'width:320px;'> </td>
                                {else}
                                        <td class="small cellText" width="70%"> {$CONSUMER_KEY} </td>
                                {/if}
                        </tr>
                        <tr>
                                <td class="small cellText big" width="30%" ><strong> Consumer Secret </strong></td>
                                {if $MODE eq 'EDIT'}
                                       <td class = "small cellText" width = "70%"> <input type = "text" name = "consumer_secret" id = "consumer_secret" value = "{$CONSUMER_SECRET}" class = "input-xlarge" style = 'width:320px;'> </td>
                                {else}
                                        <td class="small cellText" width="70%"> {$CONSUMER_SECRET} </td>
                                {/if}
                        </tr>
                        <tr>
                                <td class="small cellText big" width="30%" ><strong> Service </strong></td>
                                {if $MODE eq 'EDIT'}
				       <td class="small cellText" width="70%">
				       		<input type = "radio" name = "service" id = "Cron" onclick="tooglecron(this.id);" value = "Cron" {if $SERVICE eq 'Cron' } checked  {/if} > Cron
				       		<input type = "radio" name = "service" id = "Oncreate" onclick="tooglecron(this.id);" value = "OnCreate"  {if $SERVICE eq 'OnCreate' } checked  {/if}> OnCreate
					</td>
                                {else}
                                        <td class="small cellText" width="70%"> {$SERVICE} </td>
                                {/if}
                        </tr>
			<tr>
                                <td class="small cellText big" width="30%" ><strong> Duplicate Handling </strong></td>
                                {if $MODE eq 'EDIT'}
                                       <td class="small cellText" width="70%">
                                                <input type = "radio" name = "duplicate_handling" id = "skip" value = "Skip" {if $DUPLICATE_HANDLING eq 'Skip' } checked  {/if} > Skip
                                                <input type = "radio" name = "duplicate_handling" id = "addsuffix" value = "Add Suffix"  {if $DUPLICATE_HANDLING eq 'Add Suffix' } checked  {/if}> Add Suffix
                                        </td>
                                {else}
                                        <td class="small cellText" width="70%"> {$DUPLICATE_HANDLING} </td>
                                {/if}

                        </tr>
                </table>
		<br>
		<div> 
			<input type = 'button' name = 'resetqbtiger' id = 'resetqbtiger' class = 'btn' value = 'Reset QBTiger Sync Details' onclick = 'resetQBTiger();' style = 'background-color: #D9534F; float:left; border-color: #D43F3A;color:white !important;'>  
			<div id = "loadingImage_resetQBTiger" class = "loadingGif" style = "float:left"><img src = "modules/{$currentModule}/images/loading.gif" alt = "Loading"> </div> 
			<strong style = 'float:right'><a href = "https://developer.intuit.com/docs/0050_quickbooks_api/0002_create_a_quickbooks_app" target = "_blank">Click Here</a> to Create App </strong><br>
	                <div style = "float:right"> <strong> ( In Order To Use the Cron Service, User has to configure Cron ) </strong> </div> 
		</div>
</div>
</form>
