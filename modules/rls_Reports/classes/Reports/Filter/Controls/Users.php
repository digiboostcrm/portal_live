<?php
namespace Reports\Filter\Controls;

/**
 * This class is intended for handling Control of Users type
 * 
 * @package Reports.Filter.Controls
 * */
class Users extends Multiselect
{
    /**
     * Retrieves all users from DB and making the array
     * 
     * @return array
     * */
    public function getOptions()
    {
        $user   = loadBean('Users');
        $result = $user->get_list();
        $list   = array('' => '');
        
        foreach ($result['list'] as $user){
            $list[$user->id] = $user->name;
        }
        
        $this->setOptions($list);
        
        return parent::getOptions();
    }
    
}
