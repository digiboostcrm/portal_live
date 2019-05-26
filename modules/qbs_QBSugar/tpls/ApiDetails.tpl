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
    background: none repeat scroll 0 0 #B9C9FE;
    border-bottom: 1px solid #FFFFFF;
    border-top: 4px solid #AABCFE;
    color: #003399;
    font-size: 13px;
    font-weight: normal;
    padding: 8px;
}

#box-table-a td {
    background: none repeat scroll 0 0 #E8EDFF;
    border-bottom: 1px solid #FFFFFF;
    border-top: 1px solid transparent;
    color: #666699;
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
	height:400px;
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
<script type = "text/javascript">
$(document).ready(function() {;
    var url = document.location;
    if (window.location.href.indexOf("msg") > -1) {

        var viewModeCaptured = /msg=([^&]+)/.exec(url)[1];
        msg = viewModeCaptured ? viewModeCaptured : "myDefaultValue";
	if(msg == 'reset' || msg == 'reset#'){
	    var message = 'Reset successfully'+ '<span class="pull-right" style = "cursor:pointer;" onclick = "clearNotificationArea(\'showmessage\');">X</span>';
	qbshowmessage(message);
	}
	else{
            var message = 'TaxCode synced successfully'+ '<span class="pull-right" style = "cursor:pointer;" onclick = "clearNotificationArea(\'showmessage\');">X</span>';
           qbshowmessage(message);
	}
    }
});
function clearNotificationArea(id) {
    jQuery('#' + id).html('');
    jQuery('#' + id).removeClass();
}

function showCC(checked)	{
	if(checked == true)
		document.getElementById('bccdiv').style.display = "block";
	else if(checked == false)
		document.getElementById('bccdiv').style.display = "none";

}

function qbshowmessage(msg)	{
	$('#showmessage').show();
	$('#showmessage').width('220px');
	$('#showmessage').addClass("message");
	$('#showmessage').html(msg);
}


function closediv(divid)
{
	$('#'+divid).hide();
}

function redirect(url)	{
	window.location.href = url;
}

</script>
{/literal}
<br>
<div class = "message success" style = "padding-left: 5%;width:400px;margin-left: 300px;display:none" id = "showmessage">
</div>
<form action="#" method="post" name="qbtigersettings">
<div class = "mainQBTiger">
	<div id = "API_SHOW" style = "display:{$api_show};float:left;width:800px;margin-left: 50px;" class = "detail detail508 expanded" > <br>
		<div class = 'widget_header row-fluid'>
                        <div class = 'span1'>
                                <img width="48" height="48" border="0" title="qbtiger" alt="qbtiger" src="modules/{$currentModule}/images/qbtiger.png" style="float:left;padding: 5px;">
                                <div class = "span8" style = "float:left;padding: 5px;"> <h2> Settings </h2> </div>
                        </div>
                </div>
		<hr>
	        <div style="border: 2px solid #F08377;text-align: center; padding: 8px;">
		   Click <b>below button</b> to sync taxcode from QuickBooks.<br>
                   <br>
                   <input type = "submit" value = "Sync TaxCode" id = "qbaction" name = "qbaction" class="btn">
                   <br>
	        </div><br>
        	<div style="border: 2px solid #F08377;text-align: center; padding: 8px;">
	     	   Clicking this button will reset all the QB entries which are stored in SuiteCRM. After Reset, QB module will work like new one. Use this when you are changing the QB account
	           <br>
	           <input type = "submit" value = "Reset QB" id = "qbaction" name = "qbaction" class="btn">
          	   <br>
	        </div>

</div>
</form>


