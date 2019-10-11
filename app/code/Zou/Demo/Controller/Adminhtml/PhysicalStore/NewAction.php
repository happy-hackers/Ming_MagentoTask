<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

class NewAction extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
