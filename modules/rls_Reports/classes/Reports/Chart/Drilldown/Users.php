<?php
namespace Reports\Chart\Drilldown;

/**
 * Class extention drilldown for grouping by users.
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Chart.Drilldown
 */
class Users extends Basic
{

    /**
     * Generate search url for current grouping by date/user etc.
     * 
     * @param array row data from querry
     * @param int group_index index of current grouping
     * @return string
     */
    public function getReplaceUrl(array $row, $group_index)
    { 
        $parameter_name = '';
        $parameter_value = '';
        $grouping  = \Reports\Data\Grouping::load();
        $drilldown = \Reports\Chart\Drilldown::getInstance();

        $parameter_name = $drilldown->getAssociatedName(
            $grouping->getFieldname($group_index, $forQuerry = false),
            $grouping->getModule($group_index)
        );

        $parameter_value = $this->getIdbyName(
            $row[$grouping->getQueriedName($group_index)]
        );
      
        return $parameter_name . '=' . $parameter_value;
    }

    /**
     * Get user ID by user name.
     * 
     * @param string user_name login name of user
     * @return string
     */
    public function getIdbyName($user_name)
    {
        $user_id = '';
        $users = loadbean('Users');
        $list_users = $users->get_full_list('', 'users.user_name="' . $user_name .'"');
        if ($list_users) {
            $user_id = $list_users[0]->id;
        }
        
        return $user_id;
    }
}
