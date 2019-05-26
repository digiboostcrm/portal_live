<?php

// Definition of namespace for Reports
namespace Reports {
      
      /**
       * Autoload function.
       * 
       * @return void
       * @param string $class_name    Name of the class existent
       * 
       * */
      function __autoload($class_name)
      {
            $name_parts = explode("\\", $class_name);
            $Namespace  = $name_parts[0];
            
            //unset($name_parts[0]);

            if ($Namespace === __NAMESPACE__
                and file_exists('modules/rls_Reports/classes/'.implode('/', $name_parts).'.php')
            ){
                      
                  include_once 'modules/rls_Reports/classes/'.implode('/', $name_parts).'.php';
            }
      }
      
      spl_autoload_register(__NAMESPACE__.'\__autoload');
}

// Definition of namespace for Dashboard
namespace Dashboard {
      
      /**
       * Autoload function.
       * 
       * @return void
       * @param string $class_name    Name of the class existent
       * 
       * */
      function __autoload($class_name)
      {
            $name_parts = explode("\\", $class_name);
            $Namespace  = $name_parts[0];
            
            //unset($name_parts[0]);

            if ($Namespace === __NAMESPACE__
                and file_exists('modules/rls_Reports/classes/'.implode('/', $name_parts).'.php')
            ){
                      
                  include_once 'modules/rls_Reports/classes/'.implode('/', $name_parts).'.php';
            }
      }
      
      spl_autoload_register(__NAMESPACE__.'\__autoload');
}
