<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for String condition.
 *
 * */
class String extends Condition
{
    /**
     * The list of options
     * 
     * @var array
     * */
    protected $optionsList = array(
        'Starts With' => 'Starts With',
        'Ends With' => 'Ends With',
        'Equals' => 'Equals',
        'Does Not Equal' => 'Does Not Equal',
        'Contains' => 'Contains',
        'Does Not Contain' => 'Does Not Contain',
        'Is Empty' => 'Is Empty',
        'Is Not Empty' => 'Is Not Empty',
    );

    /**
     * The list of criterias for options
     * 
     * */
    protected $criteriaList = array(
        'Equals' => 'FIELD_NAME = "VALUE"',
        'Does Not Equal' => 'FIELD_NAME <> "VALUE"',
        'Contains' => 'FIELD_NAME like "%VALUE%"',
        'Does Not Contain' => 'FIELD_NAME not like "%VALUE%"',
        'Starts With' => 'FIELD_NAME like "VALUE%"',
        'Ends With' => 'FIELD_NAME like "%VALUE"',
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
        
        if (!is_array($saved_value)){
            $saved_value = array($saved_value);
        }
        
        if ($saved_value[0]
            or ($settings['condition'] == 'Is Empty') 
            or ($settings['condition'] == 'Is Not Empty') 
        
        ){
            $criteria_string = str_replace(
                array(
                    'FIELD_NAME',
                    'VALUE',
                ),
                array(
                    $settings['field_name'],
                    addslashes(html_entity_decode($saved_value[0], ENT_QUOTES, 'UTF-8'))
                ),
                $criteria_template
            );
        }
        
        return $criteria_string;
    }

    
}
