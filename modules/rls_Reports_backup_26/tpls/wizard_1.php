<?php
    require 'modules/rls_Reports/functions/wizardStepOne.php';
    $modules_columns = getStepOneColumnsHtml();
?>


<table width="100%" cellspacing="1" cellpadding="0" border="0">   
  <tr>
    <td  id="name_label" >
        Find Module:
        <input type="text" name="search_module" id="search_module" size="30" maxlength="150" value=""  >
    </td>
  </tr>
  <tr valign="top">
    <td width="35%">
      <table cellspacing="2" cellpadding="0" border="0" class="left_column">  
        <tbody>

          <?php echo (isset($modules_columns['left_column']) ? $modules_columns['left_column'] : ''); ?>

        </tbody>
      </table>
    </td>
    
    <td width="10%">&nbsp;</td>
    
    
    <!-- *************************** Next Column *************************** -->
    
    
    <td width="35%">
      <table cellspacing="2" cellpadding="0" border="0" class="right_column">  
        <tbody>
        
          <?php echo (isset($modules_columns['right_column']) ? $modules_columns['right_column'] : ''); ?>

        </tbody>
      </table>
    </td>       
    <td width="20%">&nbsp;</td>
  </tr>
</table>
