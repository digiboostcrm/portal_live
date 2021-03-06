<?php
    require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

    class SugarFieldPhoto extends SugarFieldBase {
       function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
           global $app_strings;
           if(!isset($displayParams['id'])) {
               $error = $app_strings['ERR_SMARTY_MISSING_DISPLAY_PARAMS'] . 'id';
               $GLOBALS['log']->error($error); 
               $this->ss->trigger_error($error);
               return;
          } 
 
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch('custom/include/SugarFields/Fields/Photo/DetailView.tpl');
      }
    }
    ?>
