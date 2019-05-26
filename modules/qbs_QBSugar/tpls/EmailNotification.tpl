<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->
{literal}
<style>
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

.left_sidebar_link      {
        margin-bottom: 5px;
        cursor: pointer;
        padding: 5% 0 0 11%;
        height: 28px;
        border-bottom: 1px solid #dedede;
}

.left_sidebar_link:hover        {
        background-color: #dedede;
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
	width:1200px;
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
function showCC(checked)
{
	if(checked == true)
	{
		document.getElementById('bccdiv').style.display = "block";
	}
	else if(checked == false)
	{
		document.getElementById('bccdiv').style.display = "none";
	}
}
</script>
{/literal}
<br>
<div class = "message success" style = "padding-left: 5%;width:400px;margin-left: 300px;display:none" id = "showmessage">
</div>
<form action="#" method="post" name="qbtigersettings">
<div class = "mainQBTiger">
	{include file = "modules/$currentModule/tpls/LeftSideBar.tpl"}
</div>

<div style = "float:left;width:800px;padding-left:20px;margin-left: 50px;" class = "detail view detail508 expanded"> <br>
	<div class = 'widget_header row-fluid' style = 'height: 75px;'>
        	<div class = 'span1'>
                	<img width="48" height="48" border="0" title="qbtiger" alt="qbtiger" src="modules/{$currentModule}/images/qbtiger.png" style="float:left;padding: 5px;">
                	<div class = "span8" style="float:left;padding: 5px;"> <h3>Email Notification</h3> <i>User can add Emails to get the Notification mail</i> </div>
			<div class = "span3" style = "float:right;padding-right:25px;padding-top:25px">
                                {if $MODE eq 'EDIT'}
                                        <input type = "submit" value = "Save" id = "qbaction" name = "qbaction" class="btn">
                                {else}
                                        <input type = 'hidden' value = 'EDIT' name = 'MODE'>
                                	<input type = "submit" value = "Edit" id = "qbaction" name = "qbaction" class="btn">
                        	{/if}
                        </div>
        	</div>
        </div>

        <table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">
  	      <tr>
         	     <td width = "40%">
                	     Email Notification
                     </td>
                     <td>
			{if $MODE eq 'EDIT'}
                        	<input type = "checkbox" name = "emailnotification" {if $EMAILNOTIFICATION eq 'on'} checked {/if} id = "emailnotification" onclick = "showCC(this.checked);">
				{else}
					{$EMAILNOTIFICATION}
				{/if}
                     </td>
               </tr>
	</table>

			<div id = "bccdiv" style = "display:{if $EMAILNOTIFICATION eq 'on'} block {else} none {/if}"  >
			<table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">
				<tr>
					<td width = "40%" style = "border-bottom: 2px solid #EAEAEA;">
						TO
					</td>
					<td style = "border-bottom: 2px solid #EAEAEA;">
					{if $MODE eq 'EDIT'}
						<input type = "text" name = "to" id = "to" class = "detailedViewTextBox" value = "{$TO}" size = "40">
					{else}
						{$TO}
					{/if}
					</td>
				</tr>

				<tr>
					<td width = "40%" style = "border-bottom: 2px solid #EAEAEA;">
						CC
					</td>
					<td style = "border-bottom: 2px solid #EAEAEA;">
					{if $MODE eq 'EDIT'}
						<input type = "text" name = "cc" id = "cc" class = "detailedViewTextBox" value = "{$CC}" size = "40">
					{else}
						{$CC}
					{/if}
					</td>
				</tr>
				<tr>
					<td>
						BCC
					</td>
					<td>
					{if $MODE eq 'EDIT'}
						<input type = "text" name = "bcc" id = "bcc" class = "detailedViewTextBox" value = "{$BCC}" size = "40">
                                        {else}
                                                {$BCC}
                                        {/if}

					</td>
				</tr>
			</table>
			</div>

			<table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">
                                <tr>
                                        <td width = "40%">
                                                Notification
                                        </td>
                                        <td>
						{$NOTIFICATION}
                                        </td>
                                </tr>
			</table>
		<br>
</div>
</form>
