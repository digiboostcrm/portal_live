<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for Multiselect condition.
 *
 * */
class Dropdown extends Condition
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
        'Equals' => 'FIELD_NAME IN ("VALUE")',
        'Does Not Equal' => 'FIELD_NAME NOT IN ("VALUE")',
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
        
        if ($values = implode('","', $saved_value)) {
            $criteria_string = str_replace(
                array(
                    'FIELD_NAME',
                    'VALUE',
                ),
                array(
                    $settings['field_name'],
                    $values,
                ),
                $criteria_template
            );
        }
        return $criteria_string;
    }
    
}
