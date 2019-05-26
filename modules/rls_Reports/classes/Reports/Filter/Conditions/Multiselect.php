<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for Multiselect condition.
 *
 * */
class Multiselect extends Condition
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
        'Equals' => 'FIELD_NAME like "%^VALUE^%"',
        'Does Not Equal' => 'FIELD_NAME not like "%^VALUE^%"',
    );
    
    /**
     * The list of criterias for options
     * 
     * */
    protected $operatorsList = array(
        'Equals' => ' OR ',
        'Does Not Equal' => ' AND ',
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
        
        if ($saved_value) {
            $sub_criteria = '';
            $sub_criteria_string = str_replace(
                    'FIELD_NAME',
                    $settings['field_name'],
                    $criteria_template
                );
            foreach ($saved_value as $value) {
                if ($value) {
                    if ($criteria_string) {
                        $criteria_string .= $this->operatorsList[$settings['condition']];
                    }
                    $criteria_string .= str_replace(
                        'VALUE',
                        $value,
                        $sub_criteria_string
                    );
                }
            }
            if(!empty($criteria_string)){
                $criteria_string = ' ( ' . $criteria_string . ' ) ';
            }

        }
        return $criteria_string;
    }
    
}

















