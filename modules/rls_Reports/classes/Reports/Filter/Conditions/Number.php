<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for Number condition.
 *
 * */
class Number extends Condition
{
    /**
     * The list of options
     * 
     * @var array
     * */
    protected $optionsList = array(
        'Equals' => 'Equals',
        'Does Not Equal' => 'Does Not Equal',
        'Less Than' => 'Less Than',
        'Greater Than' => 'Greater Than',
        'Is Between' => 'Is Between',
        'Is Empty' => 'Is Empty',
        'Is Not Empty' => 'Is Not Empty',
    );
    
    /**
     * The list of criterias for options
     * 
     * */
    protected $criteriaList = array(
        'Equals' => 'FIELD_NAME = VALUE',
        'Does Not Equal' => 'FIELD_NAME <> VALUE',
        'Less Than' => 'FIELD_NAME < VALUE',
        'Greater Than' => 'FIELD_NAME > VALUE',
        'Is Between' => 'FIELD_NAME > VALUE_START AND FIELD_NAME < VALUE_END',
        'Greater Than' => 'FIELD_NAME > VALUE',
        'Is Empty' => 'FIELD_NAME = ""',
        'Is Not Empty' => 'FIELD_NAME <> ""',
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
        
        if (!is_array($saved_value)) {
            $saved_value = array($saved_value);
        }
        
        if (($saved_value[0]
            and $settings['condition'] != 'Is Between')
            or ($settings['condition'] == 'Is Between'
                and $saved_value[1] and $saved_value[2])
            or ($settings['condition'] == 'Is Empty') 
            or ($settings['condition'] == 'Is Not Empty') 
        ) {
            $criteria_string = str_replace(
                array(
                    'FIELD_NAME',
                    'VALUE_START',
                    'VALUE_END',
                    'VALUE',
                ),
                array(
                    $settings['field_name'],
                    (float)$saved_value[1],
                    (float)$saved_value[2],
                    (float)$saved_value[0],
                ),
                $criteria_template
            );
        }
        return $criteria_string;
    }

    
}
