<?php
namespace Reports\Grid\Cell;

/**
 * This class is intended for factory of type of the configurator
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class Factory
{
  
    /**
     * Load and return the Instance for type of configurator
     * 
     * @param string type The type of configurator
     * @ReturnType classes.Reports.Configurator.Fields
     */
    public static function load($column_settings)
    {
        $build_settings = array(
                'name'=>'Name',
                'enum'=>'Dropdawn',
                'dynamicenum'=>'Dropdawn',
                'multienum'=>'Multienum',
                'date'=>'Date',
                'datetime'=>'DateTime',
                'datetimecombo'=>'DateTime',
                'bool'=>'Checkbox',
                'radio'=>'Dropdawn',
                'radioenum'=>'Dropdawn',
        );
        
        if ($column_settings['field_name'] == 'first_name'
            || $column_settings['field_name'] == 'last_name'
            || $column_settings['field_name'] == 'name'
        ) {
            $type = 'Name';
        }else{

            $module_bean = loadBean($column_settings['module_of_field']);
            if (array_key_exists($module_bean->field_defs[$column_settings['field_name']]['type'], $build_settings )) {
                $type = $build_settings[$module_bean->field_defs[$column_settings['field_name']]['type']];
            } else {
                $type = 'Varchar';
            }
        }
        
        $classname = '\Reports\Grid\Cell\\'.$type;
        $pars_class = new $classname();
        return $pars_class;
    }
}

