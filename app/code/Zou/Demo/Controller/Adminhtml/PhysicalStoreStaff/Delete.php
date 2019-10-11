<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

class Delete extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    public function execute()
    {
        $physicalStoreStaffId = $this->getRequest()->getParam(static::PARAM_CRUD_ID);
        try {
            $physicalStoreStaff = $this->_physicalStoreStaffFactory->create()->setId($physicalStoreStaffId);
            $physicalStoreStaff->delete();
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
