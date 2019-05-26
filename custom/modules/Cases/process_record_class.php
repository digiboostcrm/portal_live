<?php

    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class process_record_class
    {
        function process_record_method($bean, $event, $arguments)
        {
            if($bean->priority == 'P1'){
				$bean->priority = '<div class="btn-danger" > High </div>';
				
			}
			if($bean->priority == 'P2'){
				$bean->priority = '<div class="btn-warning" > Medium </div>';
			}
			if($bean->priority == 'P3'){
				$bean->priority = '<div class="btn-primary"> Low </div>';
			}
        }
    }

?>

<style>

.btn-warning {
	text-align:center;
	color: white; 
	background-color: #4ea13e;
	border-color: #29611e;
}

.btn-warning:hover {
  color: white;
  background-color: #29611e;
  border-color: #29611e;
}

.btn-primary {
	text-align:center;
	color: #fff;
	background-color: #007bff;
	border-color: #007bff;
}

.btn-primary:hover {
  color: #fff;
  background-color: #0069d9;
  border-color: #0062cc;
}

.btn-danger {
	text-align:center;
	color: #fff;
	background-color: #dc3545;
	border-color: #dc3545;
}

.btn-danger:hover {
  color: #fff;
  background-color: #c82333;
  border-color: #bd2130;
}

</style>