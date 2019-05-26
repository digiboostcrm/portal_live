<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for Relate condition.
 *
 * */
class Relate extends Condition
{
    /**
     * The list of options
     * 
     * @var array
     * */
    protected $optionsList = array(
        'Equals' => 'Equals',
        'Does Not Equal' => 'Does Not Equal',
    );
    
    /**
     * The list of criterias for options
     * 
     * */
    protected $criteriaList = array(
        'Equals' => 'FIELD_NAME = "VALUE"',
        'Does Not Equal' => 'FIELD_NAME <> "VALUE"',
    );

    /**
     * Returns the criteria for query based on inputed value
     * 
     * @param mixed $saved_value The value which was saved in control
     * @return string criteria
     * */
    public function getCriteria($saved_value)
    {
        $settings = $this->getSettings();
        $criteria_template = $this->criteriaList[$settings['condition']];
        $criteria_string = null;
        
        if (!is_array($saved_value)){
            $saved_value = array($saved_value);
        }
        
        if (isset($saved_value['guid'])
            and $saved_value['guid']
        ) {
            $criteria_string = str_replace(
                array(
                    'FIELD_NAME',
                    'VALUE',
                ),
                array(
                    $settings['field_name'],
                    $saved_value['guid']
                ),
                $criteria_template
            );
        }
        
        return $criteria_string;
    }
    
}
