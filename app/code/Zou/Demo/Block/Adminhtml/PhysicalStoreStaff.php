<?php
namespace Zou\Demo\Block\Adminhtml;

class PhysicalStoreStaff extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_physicalStoreStaff';
        $this->_blockGroup = 'Zou_Demo';
        $this->_headerText = __('PhysicalStoreStaff');
        $this->_addButtonLabel = __('Add New PhysicalStoreStaff');
        parent::_construct();
    }
}
