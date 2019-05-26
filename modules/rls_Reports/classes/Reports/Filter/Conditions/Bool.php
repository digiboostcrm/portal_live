<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for Bool condition.
 *
 * */
class Bool extends Condition
{
    /**
     * The list of options
     * 
     * @var array
     * */
    protected $optionsList = array(
        'Equals' => 'Equals',
    );
    
    /**
     * The list of criterias for options
     * 
     * */
    protected $criteriaList = array(
        'Equals' => 'FIELD_NAME = VALUE',
    );

    /**
     * Returns the criteria for query based on inputed value
     * 
     * @param mixed $saved_value The value which was saved in control
     * @return string
     * */
    public function getCriteria($saved_value)
    {
        $settings = $this->getSettings();
        $criteria_template = $this->criteriaList[$settings['condition']];
        $criteria_string = null;
        
        if (!is_array($saved_value)){
            $saved_value = array($saved_value);
        }
        
        if (isset($saved_value[0])){
            $criteria_string = str_replace(
                array(
                    'FIELD_NAME',
                    'VALUE',
                ),
                array(
                    $settings['field_name'],
                    (int)$saved_value[0]
                ),
                $criteria_template
            );
        }
        return $criteria_string;
    }
}
