<?php
function getQBItemAccounts_qbs_QBSugar()	{
	global $db;
	$data = array();
	$getItemAccounts = $db->pquery("select * from sugar_qb_item_accounts", array());
	$rowCount = $db->getRowCount($getItemAccounts);
	$data[''] = '';
	if($rowCount > 0)	{
		while($getAccount = $db->fetchByAssoc($getItemAccounts))	{
			$data[$getAccount['accountname']] = $getAccount['accountname'];
		}
	}
	return $data;
}


function getQBAssetAccount_qbs_QBSugar()        {
        global $db;
        $data = array();
        $getItemAssetAccount = $db->pquery("select * from sugar_qb_asset_account", array());
        $rowCount = $db->getRowCount($getItemAssetAccount);
        $data[''] = '';
        if($rowCount > 0)       {
                while($getAssetAccount = $db->fetchByAssoc($getItemAssetAccount))        {
                        $data[$getAssetAccount['assetaccount']] = $getAssetAccount['assetaccount'];
                }
        }
        return $data;
}


function getQBType_qbs_QBSugar()        {
        global $db;
        $data = array();
        $getItemType = $db->pquery("select * from sugar_qb_type", array());
        $rowCount = $db->getRowCount($getItemType);
        $data[''] = '';
        if($rowCount > 0)       {
                while($getType = $db->fetchByAssoc($getItemType))        {
                        $data[$getType['type']] = $getType['type'];
                }
        }
        return $data;
}
 
