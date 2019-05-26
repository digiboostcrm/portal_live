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
.mainQBTiger {
    width: auto;
}

.leftMenu       {
        background-color: #EFECEC;
        float:left;
        width:150px;
}

#MANUAL_SHOW > div {
    float: left;
    padding-left:50px;
}

.leftMenuItems  {
    line-height: 2em;
    margin-right: 10px;
    overflow: hidden;
    padding-left: 20px;
    position: relative;
    text-overflow: ellipsis;
    white-space: nowrap;
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


#QBResult
{
	font-size: 12px;
}

.QBBold
{
	font-weight: bold;
}

.loadingGif
{
	display:none;
}

#QBNone
{
	float:left;
	padding:5px;
}
</style>

<script type = "text/javascript">
function changemod(val)
{
	if(val.trim().length != 0)	{
		window.location.href = "index.php?module=qbs_QBSugar&action=Mapping&selmodule="+val;
	}
}

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

function checkCRMFields(count)
{
	var fvalue, ovalue;
	for(var i = 1; i <= count; i ++)
	{
		fvalue = document.getElementById('CRMField_'+i).value;
		if(fvalue.trim() != '')
		{
			for(var j = i + 1; j <= count; j ++)
			{
				var field = document.getElementById('CRMField_'+j);
				if(field.value == fvalue)	{
					var fieldtext = field.options[field.selectedIndex].text;
					alert(fieldtext+' - CRM Field is mapped more than Once');
					return false;
				}
			}
		}
	}
	return true;
}

function redirect(url)  {
        window.location.href = url;
}
</script>
{/literal}
<br>
<div class = "mainQBTiger">
	<div id = "QBQUEUE_SHOW" style = "width:1000px;padding-left:50px;" class = "detail detail508 expanded">
	        <form action= "index.php?module={$currentModule}&action=Mapping" method="post" name="fieldmapping" onSubmit = "return checkCRMFields('{$QBfieldcount}')">
		<input type = 'hidden' name = 'mode' value = 'Save'>
		<div class = 'widget_header row-fluid' style = 'height: 75px;'>
                        <div class = 'span1'>
                                <img width="48" height="48" border="0" title="qbtiger" alt="qbtiger" src="modules/{$currentModule}/images/qbtiger.png" style="float:left;padding: 5px;">
                                <div class = "span8" style="float:left;padding: 5px;"> <h3>Field Mapping</h3> <i>User can map QuickBooks Fields to SugarCRM Fields</i> </div>
                        </div>
                </div>
		<div style = 'margin-left:45%;'> {$msg} </div>
		<div> 
			<b style = "width:25%"> Select Module: </b>
			<span style = "padding-left:50px;"> {$moduleDD} </span>
		</div>
		<br>
		<div>
			<b style = "width:25%"> Sync Records: </b>
			<span style = "padding-left:50px;"> {$recordselDD} </span>
		</div>
		<hr>
		<br>
		<div>
			<b style = "width:25%"> Default Mapped Fields </b>
			<span style = "padding-left:35px;"> {$mandatoryFields} </span>
		</div>
		<hr>
		<br>
		<div>
			<b> Map Fields {$selectedmod} </b>
			{$mapfields}
		</div>	
		<br>
		<hr>
		{if $selmodule eq 'SalesOrder' || $selmodule eq 'Invoice' || $selmodule eq 'Quotes'}
		<div style = 'height: 100px;'>
			<b>Selected Status only synced to QuickBooks <i> (if nothing selected, all status records will be synced to quickbooks) </i></b> <br/> <br> 
			{$statussync}
		</div>
		<br> <hr>
		{/if}
		<div> <input type = "submit" value = "Save" class = "btn">  </div>
        	</form>
<br> <br>
</div>
