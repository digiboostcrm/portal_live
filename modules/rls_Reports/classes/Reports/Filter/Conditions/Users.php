<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for Users condition.
 *
 * */
class Users extends Multiselect
{

    /**
     * The list of criterias for options
     * 
     * */
    protected $criteriaList = array(
        'Equals' => 'FIELD_NAME like "%VALUE%"',
        'Does Not Equal' => 'FIELD_NAME not like "%VALUE%"',
    );
    
}
