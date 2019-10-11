<?php
namespace Zou\Demo\Block\Adminhtml;

class PhysicalStore extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_physicalStore';
        $this->_blockGroup = 'Zou_Demo';
        $this->_headerText = __('PhysicalStores');
        $this->_addButtonLabel = __('Add New PhysicalStore');
        parent::_construct();
    }
}
