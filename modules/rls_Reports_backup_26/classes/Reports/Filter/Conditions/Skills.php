<?php
namespace Reports\Filter\Conditions;

use Reports\Data\Criterion;

/**
 *  Base class for Periods condition.
 *
 * */
class Skills extends Condition
{
    /**
     * The list of options
     *
     * @var array
     * */
    protected $optionsList
        = array(
            'Equals' => 'Equals',

        );

    /**
     * The list of criterias for options
     *
     * */
    protected $criteriaList
        = array(
            // Yesterday
            'Equals' => 'FIELD_NAME = VALUE',
        );

    /**
     * Returns the criteria for query based on inputed value
     *
     * @param mixed $saved_value The value which was saved in control
     *
     * @return string criteria
     * */
    public function getCriteria($saved_value)
    {
        $settings = $this->getSettings();
        $criteria_string = '';
        $grid_json = $saved_value;
        $grid_json = htmlspecialchars_decode($grid_json, ENT_QUOTES);
        $grid_data = json_decode($grid_json);


        $res = $this->processNodeSearch($grid_data, new \RLS_SkillsList());
        $sql = $res['sql'];
        $skills_num = $res['skills_num'];


        if (!empty ($sql)) {
            $sql = substr($sql, 0, -4);
        } // last 'OR' delete

        if ($skills_num) {
            $module_bean = loadBean($settings['module']);
            $table = $module_bean->table_name;
            $criteria_string .= " " . $table . ".id in (
                    SELECT owner_id FROM (
                        SELECT *
                        FROM rls_skillslist LEFT JOIN rls_skillslist_cstm ON rls_skillslist.id = rls_skillslist_cstm.id_c
                        WHERE rls_skillslist.owner_module = '"
                . $settings['module'] . "'
                        AND rls_skillslist.is_checked > ''
                        AND rls_skillslist.deleted=0
                    ) t1
                    LEFT JOIN " . $table . " ON t1.owner_id = " . $table . ".id
                    WHERE " . $table . ".deleted=0
                    AND ({$sql}) GROUP BY 1 HAVING COUNT(*) >= {$skills_num}
                )";
        }


        return $criteria_string;
    }


    /**
     * Returns sql and skills_num
     *
     * @param array $node records list
     *
     * @param RLS_SkillsList object $skills_list
     *
     * @return array
     * */
    function processNodeSearch($node, $skills_list)
    {
        $res = array('sql' => '', 'skills_num' => 0);
        foreach ($node as $skill) {

            if (!empty ($skill->records)) {
                $res_tmp = $this->processNodeSearch($skill->records,
                    $skills_list);
                $res['sql'] .= $res_tmp['sql'];
                $res['skills_num'] += $res_tmp['skills_num'];
            } else {
                if (empty ($skill->checked)) {
                    continue;
                }

                $res['sql'] .= "(t1.rls_skills_id_c='{$skill->name}'";
                $res['skills_num']++;
                if ($skill->checkedBtn == 'or') {
                    $res['skills_num'] = 1;
                }

                foreach ($skill as $key => $field) {
                    if (substr($key, -2) == '_c' && isset ($field)
                        && $field !== ''
                    ) {
                        if (!empty ($skills_list->field_defs[$key]['type'])) {
                            switch ($skills_list->field_defs[$key]['type']) {
                                case 'date':
                                case 'datetime':
                                    $res['sql'] .= " AND t1.{$key} >= '{$field}'";
                                    break;
                                case 'enum':
                                    $res['sql'] .= " AND (";
                                    foreach (explode(',', $field) as $item) {
                                        $res['sql'] .= "t1.{$key} = '{$item}' OR ";
                                    }
                                    $res['sql'] = substr($res['sql'], 0, -4)
                                        . ")"; // last 'OR' delete
                                    break;
                                default:
                                    $res['sql'] .= " AND t1.{$key} = '{$field}'";
                            }
                        }
                    }
                }
                $res['sql'] .= ") OR ";
            }
        }
        return ($res);
    }


    /**
     * Get condition html.
     *
     * @param array $current_value selected data
     *
     * @return string html for field condition
     * */
    public function getHtml($current_value = null)
    {
        $settings = $this->getSettings();
        $list = $this->getOptions();

        $html = '<select name="wizard[DisplayFilters]['
            . $settings['control_name'] . '_' . $settings['field_guide']
            . '][condition][]" id="filter_condition_values-'
            . $settings['control_name'] . '_' . $settings['field_guide'] . '">';
        $html .= get_select_options_with_id($list, $current_value);
        $html .= '</select>';

        return $html;
    }
}
