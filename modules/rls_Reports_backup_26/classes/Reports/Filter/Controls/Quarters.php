<?php
namespace Reports\Filter\Controls;

/**
 * This class is intended for handling Control of Quarters type
 * 
 * @package Reports.Filter.Controls
 * */
class Quarters extends Multiselect
{
    /**
     * Gets the options for Period Control
     * 
     * @return array
     * */
    public function getOptions()
    {
        $this->setOptions(array(
            'Q1' => 'Q1',
            'Q2' => 'Q2',
            'Q3' => 'Q3',
            'Q4' => 'Q4',
        ));
        
        return parent::getOptions();
    }
}
