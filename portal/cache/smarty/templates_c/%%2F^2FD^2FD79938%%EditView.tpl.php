<?php /* Smarty version 2.6.31, created on 2019-05-24 11:29:21
         compiled from custom/include/SugarFields/Fields/Multiupload/EditView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugarvar', 'custom/include/SugarFields/Fields/Multiupload/EditView.tpl', 3, false),)), $this); ?>
<script type="text/javascript" src='{sugar_getjspath file="custom/include/SugarFields/Fields/Multiupload/SugarFieldMultiupload.js"}'></script>

<?php ob_start(); ?><?php echo smarty_function_sugarvar(array('key' => 'name'), $this);?>
<?php $this->_smarty_vars['capture']['idName'] = ob_get_contents();  $this->assign('idName', ob_get_contents());ob_end_clean(); ?>
<?php if (! empty ( $this->_tpl_vars['displayParams']['idName'] )): ?>
    <?php $this->assign('idName', $this->_tpl_vars['displayParams']['idName']); ?>
<?php endif; ?>

{if !empty($fields.id.value)}

    {assign var="file_names" value=<?php echo smarty_function_sugarvar(array('key' => 'value','stringFormat' => true), $this);?>
 }
    
    {assign var="record_id" value=$fields.id.value}
         
    <div id="drop-zone_<?php echo $this->_tpl_vars['idName']; ?>
"> 
     <input type="hidden" name="file_names" id="file_names" value="{$file_names}" /> 
      <span class="id-ff multiple"><input type='file' name="<?php echo $this->_tpl_vars['idName']; ?>
[]" multiple="multiple" id="<?php echo $this->_tpl_vars['idName']; ?>
" value="<?php echo smarty_function_sugarvar(array('key' => 'value'), $this);?>
" onchange="fileControlChanged(this);"> </span>
      <div id='files_list_<?php echo $this->_tpl_vars['idName']; ?>
'>
        <table>
            <tr>
                <div id="loading" style="display:none"><img border='0' src='{sugar_getimagepath file="img_loading.gif"}'></div>
            </tr>
            
            {assign var=files value=":"|explode:$file_names}
            {foreach item=filename key=k from=$files}
                {if !empty($filename)}
                {assign var="filepath" value=" <?php echo $this->_tpl_vars['PATH']; ?>
$record_id/<?php echo $this->_tpl_vars['FIELD_NAME']; ?>
/$filename}
                    {if file_exists($filepath) }
                      <tr id="file_{$k}">
                        <td width="75%"> {$filename}
                          <input type='hidden' name='uploaded_files[]' value='{$filename}'/></td>
                        <td><input type='button' class='button' value='Remove' onclick='removeLine(this, "<?php echo $this->_tpl_vars['idName']; ?>
")' ></td>
                      </tr>
                    {/if}
                {/if}                    
            {/foreach}
        </table>
      </div>
    </div>
    
<script>
{literal}

function fileControlChanged(element) {
	  	// Files List
		var field_id 		= $(element).attr('id'); 
		var fileList		= "files_list_"+ field_id;
	  	var dropZoneId 		= "drop-zone_"+ field_id;
		var record_id 		= {/literal} "{$record_id}";{literal}
		var files 		= [];
		var mystrr 		= [];
		var fileNum 	= element.files.length; // Files to Upload
		var initial 	= 0;
		var allowedExt 	= new Array('pdf','doc','docx','xls','xlsx','csv','txt','rtf','zip','html','mp3','jpg','jpeg','png','gif');
		
		$('#'+fileList+' #loading').show();//show loader 
		
		for (initial; initial < fileNum; initial++) 
		{
			var fname 		= element.files[initial].name.split(".");
			var extension 	= (fname[fname.length-1]).toLowerCase();
			var isAllowed 	= allowedExt.indexOf(extension);
		
			if(isAllowed > -1)
			{
				$('#'+fileList+' tr:last').after('<tr id="file_'+ initial +'"><td width="74%">' + element.files[initial].name + '</td><td><input type="button" class="button" value="Remove" onclick="removeLine(this, \''+field_id+'\' )"></td></tr>');
			
				if(element.files[initial] !== undefined)
					mystrr.push(element.files[initial].name); 
			}
			else
			{
				alert("File extension ."+extension+" is not allowed.");
				//Remove this from Files and correct Length
				continue;
			}
		}
		
		// Update Files Name 
		$('#'+dropZoneId+' #file_names').val(mystrr);// Save File Names
		var form_data 	= new FormData(); // Creating object of FormData class
		// File Field ID
		form_data.append("file_input_id", field_id);
		// Record ID
		form_data.append("record_id", record_id);
		
		for (var i = 0; i < fileNum; i++) 
		{	 
					
			var file_name = element.files[i].name;
			
			// Only Valid Extension Files to Be Uploaded
			if(mystrr.indexOf(file_name) > -1)
			{
				form_data.append("fileToUpload[]", element.files[i]);
			}
		} // end for
		
		// Send Upload Call
		$.ajax({
			url: "multiupload.php",
			type: "POST",
			data:  form_data,
			contentType: false,
			cache: false,
			processData:false,
			success: function(data)
			{
				console.log(data);
			},
			error: function() 
			{
				alert("An error occured while uploading files. Please make sure the upload path is writable and try again.");
				return false;
			},
			complete: function (data) {
				$('#'+fileList+' #loading').hide();
			}
		}); 
		
	}; // File Change Ftn
{/literal}
</script>

{else}
  Save Record to upload files.
{/if}