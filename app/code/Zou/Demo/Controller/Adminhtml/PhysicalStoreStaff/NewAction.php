<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

class NewAction extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
