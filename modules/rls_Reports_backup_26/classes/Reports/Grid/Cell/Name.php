<?php
namespace Reports\Grid\Cell;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Grid.Cell
 */
class Name extends Basic {

    /**
     * @access public
     * @param array_11 field_settings
     * @param data_from_db
     * @ParamType field_settings array
     * @ParamType data_from_db 
     */
    public function getCelHTML(array $column, array $row) 
    {
        global $sugar_config;
        $output     = array();
        $parameters = $column['link']['parameters'];
        
        foreach ($parameters as $url_name=>$url_value){
            foreach ($row as $fieldname=>$field_value){
                if (strstr('['.$fieldname.']', $url_value)){
                    $parameters[$url_name] = str_replace('['.$fieldname.']', $field_value, $url_value);
                }
            }
            
            $output[] = $url_name.'='.$parameters[$url_name];
        }
        //return '<td><a href="'. (isset($_SERVER['HTTPS'])?'https':'http') .'://'.$_SERVER['HTTP_HOST'].'/index.php?'.implode('&', $output).'">'
        $row_value = stripcslashes($row[$column['dataField']]);
        return '<td><a href="'.$sugar_config['site_url'].'/index.php?'.implode('&', $output).'">'
                  . ($row_value?$row_value:'  ') .
               '</a></td>';
    }
}
?>
