<script src="custom/include/modules/bc_notification/js/jquery-1.11.0.min.js" ></script>
<link rel="stylesheet" type="text/css" href="custom/include/css/activityLog.css">
<link rel="stylesheet" type="text/css" href="custom/include/css/bootstrap.min.css">
<script src="custom/include/javascript/activityog.js" ></script>
  <div class="form-group pull-left">
    <input type="text" class="search form-control" placeholder="What you looking for?">
</div>
<span class="counter pull-right"></span>
<table class="table table-hover table-bordered results">
  <thead>
    <tr>
      <th width="80" >NUMBER</th>
      <th width="150">USER NAME</th>
      <th width="150">ACTIVITY TYPE</th>
      <th width="150">NAME</th>
      <th width="100">MODULE NAME</th>
      <th width="100">USER IP</th>
      <th width="100">ACTIVITY ON</th>
    </tr>
	
    <tr class="warning no-result">
      <td colspan="8"><i class="fa fa-warning"></i> No result</td>
    </tr>
  </thead>
  <tbody>
 
 
  
<?php

if(!defined('sugarEntry')) define('sugarEntry', true);

global $db;
$sql = "SELECT * FROM RECORD_ACTIVITY_LOG";
$result_query = $db->query($sql);
global $db;
$sql = "SELECT * FROM RECORD_ACTIVITY_LOG";
$result_query = $db->query($sql);
$count = 1;
while($result = $db->fetchByAssoc($result_query)){
	
	$jsonData = stripslashes(html_entity_decode($result['RECORD_DATA']));

	$resJson=json_decode($jsonData,true);
	$sRecordData = "";
/*
	if(json_last_error() === JSON_ERROR_NONE && is_array($aJsonData)){
		foreach($aJsonData as $sRecordKey => $sRecordValue){
			if(empty($sRecordValue)){
				continue;
			}
			$sRecordData .= "{$sRecordKey}: {$sRecordValue}<br/>";
		}
	}
	*/
	echo "<tr>
			<td>{$count}</td>
			<td>{$result['USER_NAME']}</td>
			<td>{$result['ACTIVITY_TYPE']}</td>
			<td>{$resJson['NAME']}</td>
			<td>{$result['MODULE_NAME']}</td>
			<td>{$result['USER_IP']}</td>
			<td>{$result['ACTIVITY_ON']}</td>
			</tr>";
	$count++;
	
}

?>    
 </tbody>
</table>


