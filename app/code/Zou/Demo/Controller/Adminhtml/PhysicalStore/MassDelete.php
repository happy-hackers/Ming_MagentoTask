<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

class MassDelete extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $physicalStoreIds = $this->getRequest()->getParam('physicalStore');
        if (!is_array($physicalStoreIds) || empty($physicalStoreIds)) {
            $this->messageManager->addError(__('Please select physicalStore(s).'));
        } else {
            $physicalStoreCollection = $this->_physicalStoreCollectionFactory->create()
                ->addFieldToFilter('id', ['in' => $physicalStoreIds]);
            try {
                foreach ($physicalStoreCollection as $physicalStore) {
                    $physicalStore->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($physicalStoreIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
