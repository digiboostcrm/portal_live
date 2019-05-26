<script type="text/javascript" src="modules/rls_Reports/js/wizard-onready.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.Control.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.Request.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.Modules.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.Fields.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.Grid.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/wizard/Wizard.Drilldown.js"></script>
 
<!-- Jquery tree lib begin -->
<script type="text/javascript" src="modules/rls_Reports/libs/jstree/jquery.jstree.js"></script>
<!-- Jquery tree lib end -->

<!-- Jquery ui lib begin -->
<!-- script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script -->
<!-- link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" -->
<!-- Jquery ui lib end -->

<div class="wizard-content">
  <input type="hidden" name="type" value="<?php if (isset($_REQUEST['root'])) {echo $_REQUEST['root'];} else {echo (isset($type)?$type:'');} ?>" id="type" />
  
  <div id="reports-wizard-navigate">
    <h4><a href="#"><?php echo $mod_strings['LBL_STEP_SELECT_REPORT'] ?></a></h4>
    <span class="reports-nav-divider"> &gt; </span> 
    <h4><a href="#" class="active"><?php echo $mod_strings['LBL_STEP_FILTERS'] ?></a></h4>
    <span class="reports-nav-divider"> &gt; </span>
    <h4><a href="#"><?php echo $mod_strings['LBL_STEP_DISPLAY_FIELDS'] ?></a></h4>
    <span class="reports-nav-divider"> &gt; </span>
    <h4><a href="#"><?php echo $mod_strings['LBL_STEP_GROUPBY'] ?></a></h4>
    <span class="reports-nav-divider"> &gt; </span>
    <h4><a href="#"><?php echo $mod_strings['LBL_STEP_SUMMARIES'] ?></a></h4>
  </div>
  
  <div class="cb">&nbsp;</div>

  <div class="">
      
      <div class="">
         <input type="hidden"
                name="drill_down"
                value="0" >
         <input type="checkbox"
                name="drill_down"
                id="drill_down"
                value="1"
                onclick="Wizard.Drilldown.reloadData(this, '<?php echo $focus->id;?>')"
                <?php echo ((isset($focus->drill_down) and $focus->drill_down)
                                    ? ' checked="checked" ' : '');
                ?>
                >
         <b><?php echo $mod_strings['LBL_DRILL_DOWN'] ?></b>
         <h4></h4>
  </div>
  
  <div id="reports-wizard-steps-container">
    <div class="reports-wizard-step-container hidden">
      <div id="reports-wizard-step-1">
        <button type="button" onclick="javascript:Wizard.Control.next()"><?php echo $mod_strings['LBL_NEXT'] ?> &gt;&gt; </button>
        <br /><br />        
        <p><?php echo $mod_strings['LBL_CLICK_ICON'] ?></p>
        <h1><?php echo $mod_strings['LBL_NOTE_ANOTHER_MODULE'] ?></h1>
        <br />
        <?php require('wizard_1.php') ?>
      </div>
    </div>
    
    <div class="reports-wizard-step-container">
      <div id="reports-wizard-step-2">
        <button type="button" onclick="javascript:Wizard.Control.previous()">&lt;&lt; <?php echo $mod_strings['LBL_BACK'] ?></button>
        <button type="button" onclick="javascript:Wizard.Control.next()"><?php echo $mod_strings['LBL_NEXT'] ?> &gt;&gt; </button>
        <?php require('wizard_2.php') ?>
      </div>
    </div>
    
    <div class="reports-wizard-step-container hidden">
      <div id="reports-wizard-step-3">
        <button type="button" onclick="javascript:Wizard.Control.previous()">&lt;&lt; <?php echo $mod_strings['LBL_BACK'] ?></button>
        <button type="button" onclick="javascript:Wizard.Control.next()"><?php echo $mod_strings['LBL_NEXT'] ?> &gt;&gt; </button>
        <?php  require('wizard_3.php') ?>
      </div>
    </div>

    <div class="reports-wizard-step-container hidden">
      <div id="reports-wizard-step-4">
        <button type="button" onclick="javascript:Wizard.Control.previous()">&lt;&lt; <?php echo $mod_strings['LBL_BACK'] ?></button>
        <button type="button" onclick="javascript:Wizard.Control.next()"><?php echo $mod_strings['LBL_NEXT'] ?> &gt;&gt; </button>
        <?php  require('wizard_4_group_by.php') ?>
      </div>
    </div>
    
    <div class="reports-wizard-step-container hidden">
      <div id="reports-wizard-step-5">
        <button type="button" onclick="javascript:Wizard.Control.previous()">&lt;&lt; <?php echo $mod_strings['LBL_BACK'] ?></button>
        <?php  require('wizard_5_summaries.php') ?>
      </div>
    </div>

  </div>

</div>

  
</div>
