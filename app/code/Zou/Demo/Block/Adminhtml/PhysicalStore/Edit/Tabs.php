<?php
namespace Zou\Demo\Block\Adminhtml\PhysicalStore\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('physicalstore_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('PhysicalStore Information'));
    }
}
