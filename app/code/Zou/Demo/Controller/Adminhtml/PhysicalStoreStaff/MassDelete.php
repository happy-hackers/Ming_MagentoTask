<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;
class MassDelete extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $physicalStoreStaffIds = $this->getRequest()->getParam('physicalStoreStaff');
        if (!is_array($physicalStoreStaffIds) || empty($physicalStoreStaffIds)) {
            $this->messageManager->addError(__('Please select physicalStoreStaff(s).'));
        } else {
            $physicalStoreStaffCollection = $this->_physicalStoreStaffCollectionFactory->create()
                ->addFieldToFilter('id', ['in' => $physicalStoreStaffIds]);
            try {
                foreach ($physicalStoreStaffCollection as $physicalStoreStaff) {
                    $physicalStoreStaff->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($physicalStoreStaffIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
