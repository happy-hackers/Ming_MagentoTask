<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

class MassStatus extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $physicalStoreStaffIds = $this->getRequest()->getParam('physicalStoreStaff');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($physicalStoreStaffIds) || empty($physicalStoreStaffIds)) {
            $this->messageManager->addError(__('Please select physicalStoreStaff(s).'));
        } else {
            try {
                $physicalStoreStaffCollection = $this->_physicalStoreStaffCollectionFactory->create()
                    ->addFieldToFilter('id', ['in' => $physicalStoreStaffIds]);

                foreach ($physicalStoreStaffCollection as $physicalStoreStaff) {
                    $physicalStoreStaff->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($physicalStoreStaffIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
