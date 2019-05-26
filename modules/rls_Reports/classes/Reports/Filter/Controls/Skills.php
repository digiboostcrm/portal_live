<?php
namespace Reports\Filter\Controls;

/**
 * This class is intended for handling Control of Periods type
 *
 * FIXME: This control should be retrieved by stnadard agregates of Sugar
 *
 * @package Reports.Filter.Controls
 * */
class Skills extends Basic
{

    /**
     * The list of filter data
     *
     * @var array
     * */
    private $filterValues = array();


    /**
     * Class construct.
     *
     * @param array $settings
     * */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
    }

    /**
     * Retrieves criterion string for SQL by control of filter
     *
     * @param mixed $saved_value Saved value for control
     *
     * @return string search criterion
     *
     * */
    public function getCriteria($saved_value)
    {
        $settings = $this->getSettings();

        $class_name = get_class($this);
        $parsed_class_name = substr($class_name,
            strrpos($class_name, '\\') + 1);
        $condition
            = \Reports\Filter\Factory::loadCondition($this->getSettings());
        $criteria = $condition->getCriteria($saved_value);

        return $criteria;
    }

    /**
     * Get HTML for DateType field.
     *
     * @param string $current_value selected value in dropdown
     *
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        if (empty($current_value)) {
            $current_value = '';
        }

        global $timedate, $app_strings;
        $settings = $this->getSettings();
        $field_guide = $settings['field_guide'];


        $listEnum = array();
        $listEnabledFields = json_decode(substr($settings['listEnabledFields'],
            9, -10));
        $fields_list = $listEnabledFields;


        if (!empty ($settings['templateSkillsId'])) {
            $templ = \BeanFactory::getBean('RLS_TemplateSkills',
                $settings['templateSkillsId']);

            if (!empty ($templ->data_treegrid)) {
                $fields_data = htmlspecialchars_decode($templ->data_treegrid,
                    ENT_QUOTES);
                $dataTreeGrid = $fields_data;
            }

            if (!empty ($templ->data_listbox)) {
                $fields_list = htmlspecialchars_decode($templ->data_listbox,
                    ENT_QUOTES);
                $dataListBox = $fields_list;
                $fields_list = json_decode($fields_list);
                foreach ($fields_list as $i => $v) {
                    if ($v->val->type == 'enum') {
                        $listEnum[] = array(
                            'fildname' => $v->name,
                            'opt'      => translate($v->val->ext)
                        );
                    }
                }
            }
        } else {
            foreach ($fields_list as $i => $v) {
                if ($v->value->type == 'enum') {
                    $listEnum[] = array(
                        'fildname' => $v->value->fildname,
                        'opt'      => translate($v->value->ext)
                    );
                }
            }
        }


        $listEnumJSON = json_encode($listEnum);

        //**************************
        global $db;
        $sql
            = "SELECT id, name FROM rls_skills WHERE deleted = 0 ORDER BY name";
        $result = $db->query($sql);

        $json_data = array();
        while ($row = $db->fetchByAssoc($result)) {
            $json_data[] = array('val' => $row['id'], 'lab' => $row['name']);
        }
        $resSkills = json_encode($json_data);
        $GLOBALS['log']->fatal('RLS getListSkills: ' . $resSkills);
        //**************************

        $colDef = $settings['listEnabledFields'];
        $colDef = str_replace('{literal}', '', $colDef);
        $colDef = str_replace('{/literal}', '', $colDef);
        $listEnumJSON = $listEnumJSON;
        $templateSkillsId = $settings['templateSkillsId'];
        $html = '';
        ob_start();
        require(dirname(__FILE__) . '/../tpls/Skills.php');
        $data_template = ob_get_contents();
        ob_end_clean();

        return $html . $data_template;
    }


}
