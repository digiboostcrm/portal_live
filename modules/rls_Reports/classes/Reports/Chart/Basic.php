<?php

namespace Reports\Chart;

/**
 * @author Richlode Solutions
 */
abstract class Basic
{
    /**
     * The dataset store for Chart.
     * 
     * @var array
     */
    protected $dataset = array();

    /**
     * The main display function.
     */
    abstract public function display();

    /**
     * Returns contains for dataset.
     * 
     * @return array
     */
    public function getDataset()
    {
        return $this->dataset;
    }

    /**
     * Sets new $dataset for Chart.
     * 
     * @return array
     */
    public function setDataset(array $dataset)
    {
        $this->dataset = $dataset;

        return $this;
    }

    /**
     * Sets the title for section of Chart.
     * 
     * Every Bar, part of Pie, part of Funnel have title/caption
     * So this caption of chart section could be dependend w/ some factor: filtering, grouping, so... 
     * 
     * @param array $row The data for current row of dataset
     *
     * @return string
     */
    protected function setSectionTitle(array $row)
    {
        return
            \Reports\Data\Grouping::load()
                ->getCaptionForGroup(0, $row);
    }

    /**
     * Returns grouping parameters for chart.
     * 
     * @return array
     */
    public function getGroupBy()
    {
        return array(
            'title',
        );
    }
    public function getDataFormatCurrency()
    {
        $criterion = \Reports\Data\Criterion::getInstance()
            ->setSettings(
                \Reports\Settings\Storage::getSettings()
            )
            ->buildCriteria();

        $table_cryterion = $criterion->getSettings();
        $_SESSION['name_currency_column'] = array();
        $_SESSION['name_summaried_field'] = array();
        foreach ($table_cryterion['grid']['columns'] as $column_index => $column_value) {
            if (array_key_exists('type', $table_cryterion['grid']['columns'][$column_index])
                && $table_cryterion['grid']['columns'][$column_index]['type'] == 'currency'
            ) {
                $_SESSION['name_currency_column'][] = $table_cryterion['grid']['columns'][$column_index]['dataField'];
            }
        }
        foreach ($table_cryterion['data']['summaries'] as $summaries_index => $summaries_value) {
            if (array_key_exists('vardefs', $table_cryterion['data']['summaries'][$summaries_index])
                && array_key_exists('type', $table_cryterion['data']['summaries'][$summaries_index]['vardefs'])
                && $table_cryterion['data']['summaries'][$summaries_index]['vardefs']['type'] == 'currency'
            ) {
                $_SESSION['name_summaried_field'][] = $table_cryterion['data']['summaries'][$summaries_index]['field_name'];
            }
        }
        $_SESSION['name_currency_column'] = array_unique($_SESSION['name_currency_column']);
        $_SESSION['name_summaried_field'] = array_unique($_SESSION['name_summaried_field']);
    }
    /**
     * Returns setup like array for base_url SugarChart setting.
     * 
     * @return array
     */
    protected function getBaseUrl()
    {
        $drilldown = \Reports\Chart\Drilldown::getInstance();
        if ($drilldown->isDrilldown()) {
            return array(
                'module' => \Reports\Chart\Drilldown::getInstance()->getSearchModuleName(),
                'action' => 'index',
                'query' => 'true',
                'searchFormTab' => 'advanced_search',
            );
        } else {
            return array();
        }
    }

    /**
     * Returns parameters for url of SugarChart drillDown function.
     * 
     * @return array
     */
    protected function getUrlParams()
    {
        $params = array();
        $drilldown = \Reports\Chart\Drilldown::getInstance();

        if ($drilldown->isDrilldown()) {
            $wizard = new \Reports\Settings\WIzard\DisplayFilters();
            $settings = $wizard->get();

            if (isset($settings['controls']) and $settings['controls']) {
                foreach ($settings['controls'] as $control) {
                    if (isset($control['field_name'])) {
                        if ($drilldown->isFilterInGrouping($control['field_name'])) {
                            continue;
                        }

                        if ($control['field_name'] == 'date_closed') {
                            $field_value = (isset($control['condition'])
                                        ? $control['condition']
                                        : array());
                        } else {
                            $field_value = (isset($control['value'])
                                        ? $control['value']
                                        : array());
                        }

                        if ($field = \Reports\Filter\Factory::loadControl($control['settings_search_url'])) {
                            $params = array_merge($params, $field->getUrlParams($field_value));
                        }
                    }
                }
            }
        }

        return $params;
    }
}
