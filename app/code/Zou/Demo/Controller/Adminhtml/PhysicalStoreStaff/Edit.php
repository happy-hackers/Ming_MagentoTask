<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

class Edit extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');
        $model = $this->_physicalStoreStaffFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This physicalStoreStaff no longer exists.'));

                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('physicalStoreStaff', $model);

        return $resultPage;
    }
}
