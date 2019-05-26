<?php
namespace Reports\Filter\Conditions;

use Reports\Data\Criterion;
/**
 *  Base class for Periods condition.
 *
 * */
class Periods extends Condition
{
    /**
     * The list of options
     * 
     * @var array
     * */
    protected $optionsList = array(
        'tp_yesterday' => 'Yesterday',
        'tp_today' => 'Today',
        'tp_tomorrow' => 'Tomorrow',
        'last_7_days' => 'Last 7 Days',
        'next_7_days' => 'Next 7 Days',
        'last_week' => 'Last Week',
        'this_week' => 'This Week',
        'next_week' => 'Next Week',
        'last_month' => 'Last Month',
        'this_month' => 'This Month',
        'next_month' => 'Next Month',
        'last_30_days' => 'Last 30 Days',
        'next_30_days' => 'Next 30 Days',
        'last_year' => 'Last Year',
        'this_year' => 'This Year',
        'next_year' => 'Next Year',
        
        // dinamic conditions
        '=' => 'Equals',
        'not_equal' => 'Does Not Equal',
        'greater_than' => 'After',
        'less_than' => 'Before',
        'between' => 'Is Between',
    );

    /**
     * The list of criterias for options
     * 
     * */
    protected $criteriaList = array(
        // Yesterday
        'tp_yesterday' => 'FIELD_NAME 
            BETWEEN (
                CURRENT_DATE() - INTERVAL 1 DAY + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + TZ_ADJUSTMENT
            )
        ',
            
        //Today
        'tp_today'     => 'FIELD_NAME 
            BETWEEN (
                CURRENT_DATE() + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + INTERVAL 1 DAY + TZ_ADJUSTMENT
            )
        ',
        
        // Tomorrow
        'tp_tomorrow'  => 'FIELD_NAME 
            BETWEEN (
                CURRENT_DATE() + INTERVAL 1 DAY + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + INTERVAL 2 DAY + TZ_ADJUSTMENT
            )
        ',

        // Last 7 days
        'last_7_days' => 'FIELD_NAME 
            BETWEEN (
                CURRENT_DATE() - INTERVAL 7 DAY + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + TZ_ADJUSTMENT
            )
        ',
        // Next 7 days
        'next_7_days' => 'FIELD_NAME 
            BETWEEN (                
                CURRENT_DATE() + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + INTERVAL 7 DAY + TZ_ADJUSTMENT
            )
        ',

        // Last week
        'last_week' => '
            YEARWEEK(
                FIELD_NAME + TZ_ADJUSTMENT,
                FIRST_DAY_OF_WEEK
            ) = YEARWEEK(
                    CURRENT_DATE() - INTERVAL 7 DAY + TZ_ADJUSTMENT,
                    FIRST_DAY_OF_WEEK
                )
        ',
        // This week
        'this_week' => '
            YEARWEEK(
                FIELD_NAME + TZ_ADJUSTMENT,
                FIRST_DAY_OF_WEEK
            ) = YEARWEEK(
                    CURRENT_DATE() + TZ_ADJUSTMENT,
                    FIRST_DAY_OF_WEEK
                )
        ',
        // This week
        'next_week' => '
            YEARWEEK(
                FIELD_NAME + TZ_ADJUSTMENT,
                FIRST_DAY_OF_WEEK
            ) = YEARWEEK(
                    CURRENT_DATE() + INTERVAL 7 DAY + TZ_ADJUSTMENT,
                    FIRST_DAY_OF_WEEK
                )
        ',
        
        // Last Month
        'last_month' => 'FIELD_NAME 
            BETWEEN (
                DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH + TZ_ADJUSTMENT, "%Y-%m-01")
            ) 
            AND (
                DATE_FORMAT(CURRENT_DATE() + TZ_ADJUSTMENT, "%Y-%m-01")
            )
        ',
        
        // This Month
        'this_month' => 'FIELD_NAME 
            BETWEEN (
                DATE_FORMAT(CURRENT_DATE() + TZ_ADJUSTMENT, "%Y-%m-01")
            )
            AND (
                DATE_FORMAT(CURRENT_DATE() + INTERVAL 1 MONTH + TZ_ADJUSTMENT, "%Y-%m-01")
            )
        ',
        
        // Next Month
        'next_month' => 'FIELD_NAME 
            BETWEEN (
                DATE_FORMAT(CURRENT_DATE() + INTERVAL 1 MONTH + TZ_ADJUSTMENT, "%Y-%m-01")
            )
            AND (
                DATE_FORMAT(CURRENT_DATE() + INTERVAL 2 MONTH + TZ_ADJUSTMENT, "%Y-%m-01")
            )
        ',
        
        // Last 30 days
        'last_30_days' => 'FIELD_NAME 
            BETWEEN (
                CURRENT_DATE() - INTERVAL 30 DAY + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + TZ_ADJUSTMENT
            )
        ',
        
        // Next 30 days
        'next_30_days' => 'FIELD_NAME 
            BETWEEN (
                CURRENT_DATE() + TZ_ADJUSTMENT
            ) 
            AND (
                CURRENT_DATE() + INTERVAL 30 DAY + TZ_ADJUSTMENT
            )
        ',
        
        // Last Year
        'last_year' => 'FIELD_NAME 
            BETWEEN (
                DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 YEAR + TZ_ADJUSTMENT, "%Y-01-01")
            ) 
            AND (
                DATE_FORMAT(CURRENT_DATE() + TZ_ADJUSTMENT, "%Y-01-01")
            )
        ',
        
        // This Year
        'this_year' => 'FIELD_NAME 
            BETWEEN (
                DATE_FORMAT(CURRENT_DATE() + TZ_ADJUSTMENT, "%Y-01-01")
            )
            AND (
                DATE_FORMAT(CURRENT_DATE() + INTERVAL 1 YEAR + TZ_ADJUSTMENT, "%Y-01-01")
            )
        ',
        
        // Next Year
        'next_year' => 'FIELD_NAME 
            BETWEEN (
                DATE_FORMAT(CURRENT_DATE() + INTERVAL 1 YEAR + TZ_ADJUSTMENT, "%Y-01-01")
            )
            AND (
                DATE_FORMAT(CURRENT_DATE() + INTERVAL 2 YEAR + TZ_ADJUSTMENT, "%Y-01-01")
            )
        ',
        
        // dinamic conditions
        // Equals
        '=' => 'FIELD_NAME 
            BETWEEN (
                "RANGE_DATE_CLOSED"  + TZ_ADJUSTMENT
            )
            AND (
                "RANGE_DATE_CLOSED" + INTERVAL 1 DAY + TZ_ADJUSTMENT - INTERVAL 1 SECOND
            )
        ',
        
        // Doesn't equal
        'not_equal' => 'FIELD_NAME 
            NOT BETWEEN (
                "RANGE_DATE_CLOSED"  + TZ_ADJUSTMENT
            )
            AND (
                "RANGE_DATE_CLOSED" + INTERVAL 1 DAY + TZ_ADJUSTMENT - INTERVAL 1 SECOND
            )
        ',
        
        // Greater than
        'greater_than' => 'FIELD_NAME > "RANGE_DATE_CLOSED" + TZ_ADJUSTMENT',
        
        // Less than
        'less_than' => 'FIELD_NAME < "RANGE_DATE_CLOSED" + TZ_ADJUSTMENT',
        
        // Custom Between
        'between' => 'FIELD_NAME 
            BETWEEN (
                "START_RANGE_DATE_CLOSED" + TZ_ADJUSTMENT
            ) 
            AND (
                "END_RANGE_DATE_CLOSED" + INTERVAL 1 DAY + TZ_ADJUSTMENT - INTERVAL 1 SECOND
            )
        ',
    );

    /**
     * Returns the criteria for query based on inputed value
     * 
     * @param mixed $saved_value The value which was saved in control
     * @return string criteria
     * */
    public function getCriteria($saved_value)
    {
        global $current_user;
        $current_user_first_day_of_week = $current_user->get_first_day_of_week();
        $first_day_of_week = 0;
        if($current_user_first_day_of_week){
            $first_day_of_week = 1;
        }
        $settings = $this->getSettings();
        $criteria_template = $this->criteriaList[$settings['condition']];
        $criteria_string = null;
        
        if (!is_array($saved_value)){
            $saved_value = array($saved_value);
        }
        
        if ($saved_value) {
            $criteria_string = str_replace(
                array(
                    'FIELD_NAME',
                    'START_RANGE_DATE_CLOSED',
                    'END_RANGE_DATE_CLOSED',
                    'RANGE_DATE_CLOSED',
                    'TZ_ADJUSTMENT',
                    'FIRST_DAY_OF_WEEK',
                ),
                array(
                    $settings['field_name'],
                    $saved_value['start_range_date_closed']['to_db'],
                    $saved_value['end_range_date_closed']['to_db'],
                    $saved_value['range_date_closed']['to_db'],
                    Criterion::getUserOffsetByTimezone(),
                    $first_day_of_week
                ),
                $criteria_template
            );
        }

        return $criteria_string;
    }

    /**
     * Get condition html.
     *
     * @param array $current_value selected data
     * @return string html for dropdown condition
     * */
    public function getHtml($current_value = null)
    {
        $settings = $this->getSettings();        
        $list     = $this->getOptions();
        
        $html  = '<select name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'].'][condition][]" id="filter_values-'.$settings['control_name'] . '_' . $settings['field_guide'].'">';
        $html .= get_select_options_with_id($list, $current_value);
        $html .= '</select>';
        
        return $html;
    }
}
