
 {literal}
    <script type="text/javascript">
		function remove(id) {			
		document.getElementById("remove").remove();	
		var a = document.getElementById('upload_photo_'+id);
		a.style.display = 'block';	
		document.getElementById("remove_"+id).value = '1';	
		} 
		
		function check_file(id,size,type) {	
			$('.alert_contact_photo_upload').remove();
			console.log(type);
			file_type = type.toLowerCase();
			if(file_type == 'image/png' || file_type == 'image/jpeg' || file_type == 'image/jpg' || file_type == 'image/gif') {
				if(size > (2 * 1024 *1024)) {
					console.log(size+'--'+2 * 1024 *1024);
					
				document.getElementById(id).value = '';		
				$('#'+id).after('<span class="alert_contact_photo_upload" style="color:red;">  Maximum file upload size is 2 MB.</span>');
				return false;	
				}
			} else {
				document.getElementById(id).value = '';
				$('#'+id).after('<span class="alert_contact_photo_upload" style="color:red;">  Only jpg,png and gif images are allowed.</span>');
				return false;	
				
			}
			
		}
		
		</script>
{/literal}

<input type="hidden" id="remove_{{sugarvar key='name'}}" name="remove_{{sugarvar key='name'}}" value="0"/>
<input type="hidden" id="old_{{sugarvar key='name'}}" name="old_{{sugarvar key='name'}}" value="{$fields[{{sugarvar key='name' stringFormat=true}}].value}"/>
<input class="upload_photo" id="upload_photo_{{sugarvar key='name'}}" onchange="javascript:check_file(this.id,this.files[0].size,this.files[0].type);" name="{{sugarvar key='name'}}" style="{if !empty({{sugarvar key='value' string=true}})}display:none;{/if}" type="file" title='{{$vardef.help}}' size="{{$displayParams.size|default:30}}" {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{elseif !empty($displayParams.maxlength)}}maxlength="{{$displayParams.maxlength}}"{{else}}maxlength="255"{{/if}} value="{$fields[{{sugarvar key='name' stringFormat=true}}].value}" {{$displayParams.field}}>

    
    {if !empty({{sugarvar key='value' string=true}})}
    <div class="remove" id="remove"> 
		
    <img src='cache/images/{$fields.{{$displayParams.id}}.value}_{{sugarvar key='value'}}' height='100'><br>
    {$fields[{{sugarvar key='name' stringFormat=true}}].value}
    <a href="javascript:void(0)" onclick="javascript:remove('{{sugarvar key='name'}}');">remove</a>
    </div>
    {/if}
 
 
