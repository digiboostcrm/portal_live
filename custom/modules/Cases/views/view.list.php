<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class CasesViewList extends ViewList {
	function __construct(){
		parent::__construct();
	}
	function display(){
				global $db;
		global $current_user;
		$user_id = $current_user->id;
		$roleID = $_REQUEST['roleID'];
		$sql = 'SELECT id, name FROM acl_roles WHERE deleted = 0';
		$res = $db->query($sql);
		$options = "<option value=''>All Ticket</option>";
		while($result = $db->fetchByAssoc($res)){
			$id = '';
			$id = $result['id'];
			$name = $result['name'];
			$selected = "";
			$rec_id = $_REQUEST['id'];
			if($name == $roleID){ 
				$selected = "selected";
			}
			$options .= "<option $selected value='$name'>$name</option>";
		}
		$button = " <select style='float : right;' id = 'roleID'> $options </select>";
		echo $button;
		
		parent :: display();
		
		echo '<script>
			$( "#roleID" ).change(function() {
				var role_id = $(this).val();
				var user_id = "'.$user_id.'";
				//alert($(this).val());return false;
				if(role_id){					
					window.location.href = "index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView&roleID="+role_id+"&id="+user_id;
				}
				else{
					
					window.location.href = "index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView";
				}
			});
		</script>';
		
	}
	function listViewProcess() {
		    
		$this->processSearchForm();
		$this->lv->searchColumns = $this->searchForm->searchColumns;
		
		if(isset($_REQUEST['search_form_only']) && $_REQUEST['search_form_only'] == true){
			return;
		}
		
		global $db;
			if(isset($_REQUEST['roleID']) && !empty($_REQUEST['roleID'])){
				$queryVal = $_REQUEST['roleID'];
				$this->params['custom_where'] = " AND cases.queue = '$queryVal' ";  
				
			}
			else{
				if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
					$this->lv->ss->assign("SEARCH", true);
					$this->lv->ss->assign('savedSearchData', $this->searchForm->getSavedSearchData());
					//$this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
					$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
				}
				if(!isset($_REQUEST['button'])){
					$this->params['custom_where'] = " AND cases.state = 'Open'";  
				}
				
			}
		/*	
		if(isset($_REQUEST['roleID']) && !empty($_REQUEST['roleID']) && $_REQUEST['roleID'] != "myTicket"){
			$roleID = $_REQUEST['roleID'];
			$query = "SELECT user_id FROM `acl_roles_users` WHERE `role_id` = '$roleID' AND deleted = 0";
			$res1 = $db->query($query);
			$user_ids = '';
			while($result = $db->fetchByAssoc($res1)){
				$user_ids .= "'".$result['user_id']."' , ";
			}
			$user_ids = substr($user_ids , 0 , -2);
			$this->params['custom_where'] = " AND cases.assigned_user_id IN ($user_ids) ";  
		}else if($_REQUEST['roleID'] == "myTicket"){
			$id = $_REQUEST['id'];
			$this->params['custom_where'] = " AND cases.assigned_user_id IN ($id) ORDER BY cases.name DESC";  
		}
		else{
			$this->params['sortOrder'] = "DESC";			
			if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
				$this->lv->ss->assign("SEARCH", true);
				$this->lv->ss->assign('savedSearchData', $this->searchForm->getSavedSearchData());
				//$this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
				$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
			}
		}
			*/
            $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);  
			echo $this->lv->display();
			
    } 

}

?>