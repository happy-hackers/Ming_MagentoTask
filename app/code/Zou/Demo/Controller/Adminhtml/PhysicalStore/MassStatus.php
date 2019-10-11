<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

class MassStatus extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $physicalStoreIds = $this->getRequest()->getParam('physicalStore');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($physicalStoreIds) || empty($physicalStoreIds)) {
            $this->messageManager->addError(__('Please select physicalStore(s).'));
        } else {
            try {
                $physicalStoreCollection = $this->_physicalStoreCollectionFactory->create()
                    ->addFieldToFilter('id', ['in' => $physicalStoreIds]);

                foreach ($physicalStoreCollection as $physicalStore) {
                    $physicalStore->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($physicalStoreIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
