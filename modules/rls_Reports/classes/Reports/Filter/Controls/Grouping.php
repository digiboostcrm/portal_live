<?php
namespace Reports\Filter\Controls;

/**
 * Class for filter control with grouping.
 * 

 * */
class Grouping extends Dropdown
{

    /**
     * Gets the options for Grouping Control
     * 
     * @return array
     * */
    public function getOptions()
    {
        $globalSettings = \Reports\Settings\Storage::getSettings();
        $optionList = array();
        foreach ($globalSettings['data']['grouping'] as $option) {
            $optionList[$option['optionName']] = $option['label'];
        }
        $this->setOptions($optionList);
        return parent::getOptions();
    }
}
