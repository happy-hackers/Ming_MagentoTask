<?php

namespace Custom\Grid\Model\Config\Source;

class ModeSwitch extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
    * Get all options
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
                ['label' => __('tennis'), 'value'=>'tennis'],
                ['label' => __('football'), 'value'=>'football'],
                ['label' => __('basketball'), 'value'=>'basketball'],
               
            ];

    return $this->_options;

    }

}