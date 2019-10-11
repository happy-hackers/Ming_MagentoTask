<?php

namespace Zou\Demo\Block\Adminhtml\PhysicalStoreStaff\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('physicalstorestaff_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('PhysicalStoreStaff Information'));
    }
}
