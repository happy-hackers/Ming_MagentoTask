<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;
class Grid extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        
        return $resultLayout;
    }
}
