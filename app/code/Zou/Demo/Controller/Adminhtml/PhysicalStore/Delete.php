<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

class Delete extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    public function execute()
    {
        $physicalStoreId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);
        try {
            $physicalStore = $this->_physicalStoreFactory->create()->setId($physicalStoreId);
            $physicalStore->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
