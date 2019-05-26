<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
function getApplicationKey()
{
    global $db,$sugar_config;
    $results = array();
    $sSql = "SELECT * FROM ut_rightsignature_api_keys WHERE deleted=0 ";
    $sSql = $db->limitQuery($sSql, 0, 1,false,'',false);
    $oRes = $db->query($sSql,true);
    $aRow = $db->fetchByAssoc($oRes);
    if(!empty($aRow)) {
        if(!empty($aRow['consumer_key']))
            $results['consumer_key'] = $aRow['consumer_key'];
        if(!empty($aRow['consumer_secret']))
        $results['consumer_secret'] = $aRow['consumer_secret'];
            
    }
    return $results;
}
function getOAuthDBSettings($sugar_user_id=1)
{
    $sugar_user_id=1;
    global $db;
    $results = array();
    $sSql = "SELECT * FROM ut_oauth_session WHERE user_fld='".$sugar_user_id."' AND server='RightSignature'";
    $oRes = $db->query($sSql,true);
    $aRow = $db->fetchByAssoc($oRes);
    //if(!empty($aRow['session']) && !empty($aRow['state']) && !empty($aRow['access_token']) 
//        && !empty($aRow['access_token_secret']) && !empty($aRow['authorized']) && ($aRow['authorized'] == 'Y' || $aRow['authorized'] == 1))
    if(!empty($aRow['session']) && !empty($aRow['state']) && !empty($aRow['access_token']) 
            && !empty($aRow['authorized']) && ($aRow['authorized'] == 'Y' || $aRow['authorized'] == 1))
    {
         $results = $aRow;
    }
    return $results;
}

function convertAllObjectToArray($obj)
{
    // Convert object to array
    if(is_object($obj))
        $obj = (array) $obj;

    // If variable is array then loop each values inside it
    if(is_array($obj)){
        foreach($obj as $key => $val){
            // if $val is object then call same function again
            if(is_object($val))
            {
                $obj[$key] = convertAllObjectToArray($val);
            }
            // if $val is array then also call same function again
            elseif(is_array($val))
            {
                $obj[$key] = convertAllObjectToArray($val);
            }
            // if $val is not array or object then fill the return array with $val
            else
            {
                $obj[$key] = $val;
            }

        }
    }
    return $obj;
}

function update_rs_dropdown($dropdown_name, $newArray)
{
    require_once('modules/ModuleBuilder/MB/ModuleBuilder.php');    
    require_once('modules/ModuleBuilder/parsers/parser.dropdown.php');
    global $app_list_strings,$current_language;
    $_REQUEST['view_package'] = 'studio'; //need this in parser.dropdown.php
    $parser = new ParserDropDown();   
    $params = array();
    $params['view_package'] = 'studio';
    $params['dropdown_name'] = $dropdown_name;
    $params['dropdown_lang'] = (!empty($params['dropdown_lang'])?$params['dropdown_lang']:$current_language);
    //$dropdownvalue =array_merge($app_list_strings[$params['dropdown_name']],$newArray);
    $dropdownvalue =$newArray;
    foreach($dropdownvalue as $key => $sValue)
        $drop_list[] = array($key, $sValue);
    if(count($drop_list) > 0)
    {
        $json = getJSONobj();
        $params['list_value'] = $json->encode( $drop_list );
        $parser->saveDropDown($params);
    }
}

/**
 * Convert input data for other fields to database friendly type
 * 
 * @param array $data - post data 
 * @return string - serialized array
 */
function prepare_other_fields($data) {
    $result = array();
    foreach (array('include', 'exclude') as $key) {
        //isset($array[$key]) ? $array[$key] : $default;
        $aOutput = isset($data[$key]) ? $data[$key] : array();
        $arr = explode(',', $aOutput);    
        $result[$key] = array_filter(array_map('trim', $arr));
    }
    return base64_encode(serialize($result));
}

/**
 * Unserialize other fields (include/exclude words), return by key as string or array
 * 
 * @param string|FALSE $key 'exclude' or 'include'
 * @param bool $as_string - return data as comma-separated string
 * @return array|string
 */
function get_other_fields($OtherFields, $key = FALSE, $as_string = FALSE) {
    $data = unserialize(base64_decode($OtherFields));
    if ($key) {
        $data = $data[$key];
        return $as_string ? implode(', ', $data) : $data;
    }
    return $data;
}
function decStrNum($n)
{
  $n = (string)$n;
  if ((int)$n == 0 || 0 === strpos($n, '-'))
    return false;
  $result = $n;
  $len = strlen($n);
  $i = $len - 1;
  while ($i > -1) {
    if ($n[$i] === "0") {
      $end = substr($result, $i + 1);
      if ($end === false) $end = '';
      $result = substr($result, 0, -($len - $i)) . "9" . $end;
      $i--;
    } else {
      $end = substr($result, $i + 1);
      if ($end === false) $end = '';
      return substr($result, 0, -($len - $i)) . ((int)$n[$i] - 1) . $end;
    }
  }
  return $result;
}
?>