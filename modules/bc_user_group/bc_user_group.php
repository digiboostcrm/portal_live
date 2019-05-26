<?PHP

/**
 * The file used to view User Group module.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once('modules/bc_user_group/bc_user_group_sugar.php');

class bc_user_group extends bc_user_group_sugar {

    function bc_user_group() {
        parent::bc_user_group_sugar();
    }

    public function save($check_notify = false)
    {
        require_once 'custom/biz/function/default_portal_module.php';
        if($this->name == 'Default'){
            $user_accessible_modules = get_modules();
                foreach ($user_accessible_modules as $key => $value) {
                    $user_accessible_modules[$key] = '^' . $key . '^';
                }
                $modules = implode(',', $user_accessible_modules);
            $this->accessible_modules = $modules;
        }
        
         parent::save($check_notify);
    }
    /**
     * This function should be overridden in each module.  It marks an item as deleted.
     *
     * If it is not overridden, then marking this type of item is not allowed
     */
    function mark_deleted($id) {
        global $current_user;
        $date_modified = $GLOBALS['timedate']->nowDb();
        if (isset($_SESSION['show_deleted'])) {
            $this->mark_undeleted($id);
        } else {
            // call the custom business logic
            $custom_logic_arguments['id'] = $id;
            $this->call_custom_logic("before_delete", $custom_logic_arguments);
            $bc_user_group = new bc_user_group();
            $bc_user_group->retrieve_by_string_fields(array('name' => 'Default'));
            $group_id = $bc_user_group->id;
            if ($id == $group_id) {
                $this->deleted = 0;
            } else {
                $this->deleted = 1;
                $this->mark_relationships_deleted($id);
                if (isset($this->field_defs['modified_user_id'])) {
                    if (!empty($current_user)) {
                        $this->modified_user_id = $current_user->id;
                    } else {
                        $this->modified_user_id = 1;
                    }
                    $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified', modified_user_id = '$this->modified_user_id' where id='$id'";
                } else {
                    $query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified' where id='$id'";
                }
                $this->db->query($query, true, "Error marking record deleted: ");

                SugarRelationship::resaveRelatedBeans();

                // Take the item off the recently viewed lists
                $tracker = new Tracker();
                $tracker->makeInvisibleForAll($id);
            }

            // call the custom business logic
            $this->call_custom_logic("after_delete", $custom_logic_arguments);
        }
    }

}
